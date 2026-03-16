<?php

namespace App\Services;

use App\Models\Analysis;
use App\Models\Food;
use App\Models\Recommendation;

class RecommendationService
{
    protected $bodyAnalysisService;

    public function __construct(BodyAnalysisService $bodyAnalysisService)
    {
        $this->bodyAnalysisService = $bodyAnalysisService;
    }

    /**
     * Generate food recommendations based on analysis
     * Uses Naive Bayes-inspired approach with rule-based filtering
     * 
     * @param Analysis $analysis
     * @return array Array of recommendations with food data
     */
    public function generateRecommendations(Analysis $analysis): array
    {
        // Get predicted diet type using Naive Bayes
        $predictedDietType = $this->bodyAnalysisService->predictDietType($analysis);
        $conditions = $this->bodyAnalysisService->detectHealthConditions($analysis);
        $dailyCalories = $this->bodyAnalysisService->calculateDailyCalories($analysis);

        // Get all foods and filter based on diet type and conditions
        $allFoods = Food::all();
        $scoredFoods = [];

        foreach ($allFoods as $food) {
            $score = $this->calculateMatchScore($food, $analysis, $predictedDietType, $conditions);
            
            if ($score > 60) { // Only include foods with >60% match
                $scoredFoods[] = [
                    'food' => $food,
                    'score' => $score,
                    'timing' => $this->determineMealTiming($food),
                ];
            }
        }

        // Sort by score (highest first)
        usort($scoredFoods, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        // Get diverse recommendations (different meal types)
        $recommendations = $this->selectDiverseRecommendations($scoredFoods, 8);

        // Add metadata
        foreach ($recommendations as &$rec) {
            $rec['diet_type'] = $predictedDietType;
            $rec['conditions'] = $conditions;
            $rec['daily_calorie_target'] = $dailyCalories;
        }

        return $recommendations;
    }

    /**
     * Calculate match score for a food based on user's health profile
     * Enhanced Naive Bayes-inspired scoring
     * 
     * @param Food $food
     * @param Analysis $analysis
     * @param string $dietType Predicted diet type
     * @param array $conditions Detected health conditions
     * @return int Match score (0-100)
     */
    private function calculateMatchScore(
        Food $food, 
        Analysis $analysis, 
        string $dietType, 
        array $conditions
    ): int
    {
        $score = 70; // Base score

        // ============================================
        // DIET TYPE MATCHING (Naive Bayes Result)
        // ============================================
        if ($dietType === 'Low_Carb') {
            if ($food->carbs < 20) {
                $score += 20;
            } elseif ($food->carbs < 40) {
                $score += 10;
            } else {
                $score -= 15; // Penalty for high carb
            }
            
            if ($food->protein > 20) $score += 10;
            if ($food->fat > 10) $score += 5;
        }

        if ($dietType === 'Low_Sodium') {
            // Since our nutrition.csv doesn't have sodium data,
            // we prefer fresh, unprocessed foods
            $lowSodiumCategories = ['Buah', 'Sayuran', 'Protein Nabati'];
            if (in_array($food->category, $lowSodiumCategories)) {
                $score += 20;
            }
        }

        if ($dietType === 'High_Protein') {
            if ($food->protein > 25) {
                $score += 25;
            } elseif ($food->protein > 15) {
                $score += 15;
            }
        }

        if ($dietType === 'Balanced') {
            // Balanced macronutrients
            $totalMacros = $food->protein + $food->carbs + $food->fat;
            if ($totalMacros > 0) {
                $proteinRatio = ($food->protein * 4) / ($totalMacros * 4) * 100;
                if ($proteinRatio >= 20 && $proteinRatio <= 35) {
                    $score += 15;
                }
            }
        }

        // ============================================
        // HEALTH CONDITION FILTERING
        // ============================================
        if (in_array('Diabetes', $conditions)) {
            // Low carb, high fiber
            if ($food->carbs < 30) $score += 15;
            if ($food->fiber >= 5) $score += 10;
            
            // Avoid high carb foods
            if ($food->carbs > 50) $score -= 20;
        }

        if (in_array('Hypertension', $conditions)) {
            // Prefer fruits, vegetables, whole grains
            $heartHealthy = ['Buah', 'Sayuran', 'Karbohidrat'];
            if (in_array($food->category, $heartHealthy)) {
                $score += 15;
            }
            
            // Avoid processed/high sodium (we assume certain categories are processed)
            if (str_contains(strtolower($food->name), 'goreng') || 
                str_contains(strtolower($food->name), 'asin')) {
                $score -= 20;
            }
        }

        if (in_array('Obesity', $conditions)) {
            // Low calorie, high fiber, moderate protein
            if ($food->calories < 200) {
                $score += 20;
            } elseif ($food->calories < 350) {
                $score += 10;
            } else {
                $score -= 10;
            }
            
            if ($food->fiber >= 5) $score += 10;
            if ($food->protein > 15) $score += 8;
        }

        if (in_array('High_Cholesterol', $conditions)) {
            // High fiber, avoid high fat
            if ($food->fiber >= 5) $score += 15;
            if ($food->fat < 10) $score += 10;
            
            // Plant-based proteins preferred
            if ($food->category === 'Protein Nabati') $score += 12;
        }

        // ============================================
        // BMI-BASED ADJUSTMENTS
        // ============================================
        if ($analysis->bmi < 18.5) {
            // Underweight - prefer higher calorie foods
            if ($food->calories > 350) $score += 12;
            if ($food->protein > 20) $score += 8;
        } elseif ($analysis->bmi >= 30) {
            // Obese - prefer lower calorie, nutrient-dense foods
            if ($food->calories < 250) $score += 15;
            if ($food->calories < 150) $score += 8;
        } elseif ($analysis->bmi >= 25) {
            // Overweight
            if ($food->calories < 300) $score += 10;
        }

        // ============================================
        // HEALTH GOAL MATCHING
        // ============================================
        switch ($analysis->health_goal) {
            case 'lose_weight':
                if ($food->calories < 250) $score += 12;
                if ($food->protein > 15) $score += 8;
                if ($food->fiber >= 5) $score += 8;
                break;
            
            case 'gain_muscle':
            case 'gain_weight':
                if ($food->protein > 25) $score += 18;
                if ($food->calories > 350) $score += 10;
                if ($food->category === 'Protein Hewani') $score += 10;
                break;
            
            case 'maintain':
            default:
                if ($food->calories >= 200 && $food->calories <= 400) $score += 10;
                break;
        }

        // ============================================
        // ACTIVITY LEVEL ADJUSTMENTS
        // ============================================
        if (in_array($analysis->activity_level, ['active', 'very_active'])) {
            // Need more energy
            if ($food->calories > 300) $score += 8;
            if ($food->protein > 20) $score += 8;
        } elseif ($analysis->activity_level === 'sedentary') {
            // Need less calories
            if ($food->calories < 300) $score += 10;
        }

        // ============================================
        // DIETARY RESTRICTION FILTERING
        // ============================================
        if ($analysis->dietary_restriction !== 'none') {
            $dietaryTags = $food->dietary_tags ?? [];
            
            if ($analysis->dietary_restriction === 'vegetarian') {
                $vegCategories = ['Sayuran', 'Buah', 'Protein Nabati', 'Karbohidrat', 'Dairy'];
                if (in_array($food->category, $vegCategories)) {
                    $score += 15;
                } else {
                    $score -= 40; // Strong penalty
                }
            } elseif ($analysis->dietary_restriction === 'vegan') {
                $veganCategories = ['Sayuran', 'Buah', 'Protein Nabati', 'Karbohidrat'];
                if (in_array($food->category, $veganCategories)) {
                    $score += 15;
                } else {
                    $score -= 40;
                }
            }
        }

        // Ensure score is within bounds
        return max(0, min(100, $score));
    }

    /**
     * Determine meal timing based on food properties
     * 
     * @param Food $food
     * @return string Timing (Sarapan, Makan Siang, Makan Malam, Camilan)
     */
    private function determineMealTiming(Food $food): string
    {
        if ($food->meal_type) {
            return $food->meal_type;
        }

        // Fallback logic based on calories and category
        if ($food->category === 'Buah' || $food->calories < 150) {
            return 'Camilan';
        }

        if (in_array($food->category, ['Karbohidrat', 'Protein Hewani'])) {
            return 'Makan Utama';
        }

        return 'Makan Utama';
    }

    /**
     * Select diverse recommendations across different meal types
     * 
     * @param array $scoredFoods
     * @param int $count
     * @return array
     */
    private function selectDiverseRecommendations(array $scoredFoods, int $count = 8): array
    {
        $selected = [];
        $categories = [];

        // First pass: get top foods from different categories
        foreach ($scoredFoods as $item) {
            $category = $item['food']->category;
            
            if (!isset($categories[$category]) || count($categories[$category]) < 2) {
                $categories[$category][] = $item;
                $selected[] = $item;
                
                if (count($selected) >= $count) {
                    break;
                }
            }
        }

        // If still need more, add remaining high-score foods
        if (count($selected) < $count) {
            foreach ($scoredFoods as $item) {
                if (!in_array($item, $selected)) {
                    $selected[] = $item;
                    if (count($selected) >= $count) {
                        break;
                    }
                }
            }
        }

        return $selected;
    }

    /**
     * Save recommendations to database
     * 
     * @param Analysis $analysis
     * @param array $recommendations
     * @return void
     */
    public function saveRecommendations(Analysis $analysis, array $recommendations): void
    {
        // Clear old recommendations for this analysis
        Recommendation::where('analysis_id', $analysis->id)->delete();

        foreach ($recommendations as $recommendation) {
            Recommendation::create([
                'analysis_id' => $analysis->id,
                'food_id' => $recommendation['food']->id,
                'match_score' => $recommendation['score'],
                'timing' => $recommendation['timing'],
            ]);
        }
    }
}

<?php

echo "=== DEMO DATA SCENARIOS ===\n\n";

$demoData = [
    'healthy' => [
        'title' => '✅ Healthy & Normal',
        'description' => 'Normal BMI, Active Lifestyle',
        'age' => 28,
        'gender' => 'Male',
        'weight' => '70 kg',
        'height' => '175 cm',
        'bmi' => round(70 / (1.75 * 1.75), 1),
        'blood_pressure' => '120/80 mmHg (Normal)',
        'blood_sugar' => '95 mg/dL (Normal)',
        'cholesterol' => '180 mg/dL (Good)',
        'activity_level' => 'Active (6-7 days/week)',
        'goal' => 'Maintain Weight',
        'expected_result' => 'Balanced diet, maintenance calories, no health warnings'
    ],
    'overweight' => [
        'title' => '⚠️ Overweight & Sedentary',
        'description' => 'High BMI, Low Activity',
        'age' => 35,
        'gender' => 'Male',
        'weight' => '95 kg',
        'height' => '170 cm',
        'bmi' => round(95 / (1.70 * 1.70), 1),
        'blood_pressure' => '135/88 mmHg (Prehypertension)',
        'blood_sugar' => '105 mg/dL (Slightly high)',
        'cholesterol' => '220 mg/dL (Borderline high)',
        'activity_level' => 'Sedentary (Little/no exercise)',
        'goal' => 'Weight Loss',
        'expected_result' => 'Low-carb diet recommendation, obesity warning, calorie deficit'
    ],
    'hypertension' => [
        'title' => '🩺 Hypertension (High Blood Pressure)',
        'description' => 'High Blood Pressure',
        'age' => 45,
        'gender' => 'Female',
        'weight' => '75 kg',
        'height' => '165 cm',
        'bmi' => round(75 / (1.65 * 1.65), 1),
        'blood_pressure' => '155/95 mmHg (Stage 2 Hypertension)',
        'blood_sugar' => '100 mg/dL (Normal)',
        'cholesterol' => '240 mg/dL (High)',
        'activity_level' => 'Light (1-3 days/week)',
        'goal' => 'Weight Loss',
        'expected_result' => 'Low-sodium diet, high blood pressure warning, heart-healthy foods'
    ],
    'diabetes' => [
        'title' => '💉 Diabetes Risk',
        'description' => 'High Blood Sugar',
        'age' => 50,
        'gender' => 'Male',
        'weight' => '88 kg',
        'height' => '172 cm',
        'bmi' => round(88 / (1.72 * 1.72), 1),
        'blood_pressure' => '140/90 mmHg (Stage 1 Hypertension)',
        'blood_sugar' => '160 mg/dL (High - Diabetes risk)',
        'cholesterol' => '230 mg/dL (High)',
        'activity_level' => 'Light (1-3 days/week)',
        'goal' => 'Weight Loss',
        'expected_result' => 'Low-carb diet, high blood sugar warning, diabetes-friendly foods'
    ],
    'underweight' => [
        'title' => '📉 Underweight',
        'description' => 'Low BMI, Need Muscle Gain',
        'age' => 22,
        'gender' => 'Female',
        'weight' => '48 kg',
        'height' => '168 cm',
        'bmi' => round(48 / (1.68 * 1.68), 1),
        'blood_pressure' => '110/70 mmHg (Normal)',
        'blood_sugar' => '85 mg/dL (Normal)',
        'cholesterol' => '160 mg/dL (Good)',
        'activity_level' => 'Moderate (3-5 days/week)',
        'goal' => 'Muscle Gain',
        'expected_result' => 'High-protein diet, underweight warning, calorie surplus'
    ]
];

foreach ($demoData as $key => $demo) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "{$demo['title']}\n";
    echo "{$demo['description']}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    echo "Personal Info:\n";
    echo "  • Age: {$demo['age']} years\n";
    echo "  • Gender: {$demo['gender']}\n";
    echo "  • Weight: {$demo['weight']}\n";
    echo "  • Height: {$demo['height']}\n";
    echo "  • BMI: {$demo['bmi']} (" . ($demo['bmi'] < 18.5 ? 'Underweight' : ($demo['bmi'] < 25 ? 'Normal' : ($demo['bmi'] < 30 ? 'Overweight' : 'Obese'))) . ")\n\n";
    
    echo "Health Metrics:\n";
    echo "  • Blood Pressure: {$demo['blood_pressure']}\n";
    echo "  • Blood Sugar: {$demo['blood_sugar']}\n";
    echo "  • Cholesterol: {$demo['cholesterol']}\n\n";
    
    echo "Lifestyle:\n";
    echo "  • Activity Level: {$demo['activity_level']}\n";
    echo "  • Goal: {$demo['goal']}\n\n";
    
    echo "Expected Analysis Result:\n";
    echo "  → {$demo['expected_result']}\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "HOW TO USE:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "1. Start server: php artisan serve\n";
echo "2. Go to: http://localhost:8000/analyze\n";
echo "3. Click any demo button (Healthy, Overweight, etc.)\n";
echo "4. Form will auto-fill with demo data\n";
echo "5. Click 'Analyze & Get Recommendations'\n";
echo "6. See different results based on health profile!\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "DIFFERENCES YOU'LL SEE:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "✓ Different BMI categories and warnings\n";
echo "✓ Different diet types (Balanced, Low-Carb, Low-Sodium, High-Protein)\n";
echo "✓ Different health condition warnings\n";
echo "✓ Different calorie targets (deficit, maintenance, surplus)\n";
echo "✓ Different food recommendations\n\n";

echo "✅ Demo data feature ready!\n";

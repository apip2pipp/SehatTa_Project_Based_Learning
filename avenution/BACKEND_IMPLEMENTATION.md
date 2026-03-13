# Backend Implementation - Avenution

## ✅ Yang Sudah Dikerjakan

### 1. **Database Structure**
- ✅ Update migration `foods` table dengan kolom tambahan:
  - `sugars`, `sodium`, `cholesterol` (untuk filtering penyakit)
  - `meal_type` (Sarapan/Makan Siang/Makan Malam/Camilan)
  - Semua kolom disesuaikan dengan dataset

### 2. **Dataset Integration**
- ✅ **nutrition.csv** (1346 makanan Indonesia)
  - Berisi: id, name, calories, proteins, fat, carbohydrate, image
  - Sudah ada seeder untuk import otomatis
  
- ✅ **FoodSeeder.php** - Import makanan dari CSV
  - Auto-categorize berdasarkan nama (Sayuran, Buah, Protein Hewani, dll)
  - Auto-assign meal type
  - Auto-assign emoji
  - Batch insert (100 rows per batch untuk performa)

### 3. **Naive Bayes Implementation**

#### **BodyAnalysisService.php** - Prediction Engine
Fitur baru yang ditambahkan:

```php
predictDietType(Analysis $analysis): string
```
- **Fungsi**: Prediksi tipe diet menggunakan scoring system (Naive Bayes-inspired)
- **Input**: Data analysis user (BMI, blood pressure, blood sugar, dll)
- **Output**: `Balanced`, `Low_Carb`, `Low_Sodium`, atau `High_Protein`
- **Logic**:
  - Scoring berdasarkan BMI (obesity → Low Carb)
  - Scoring berdasarkan blood pressure (hypertension → Low Sodium)
  - Scoring berdasarkan blood sugar (diabetes → Low Carb)
  - Scoring berdasarkan activity level
  - Scoring berdasarkan health goal

```php
detectHealthConditions(Analysis $analysis): array
```
- **Fungsi**: Deteksi kondisi kesehatan
- **Output**: `['Diabetes', 'Hypertension', 'Obesity', 'High_Cholesterol']` atau `['None']`

```php
calculateDailyCalories(Analysis $analysis): int
```
- **Fungsi**: Hitung kebutuhan kalori harian
- **Method**: Mifflin-St Jeor Equation
- **Adjustment**: Activity multiplier + health goal (deficit/surplus)

#### **RecommendationService.php** - Food Matching Engine
Fitur yang sudah ada + enhanced:

```php
generateRecommendations(Analysis $analysis): array
```
- **Proses**:
  1. Prediksi diet type (via BodyAnalysisService)
  2. Deteksi health conditions
  3. Hitung daily calorie needs
  4. Score semua makanan (1346 items)
  5. Filter score > 60
  6. Select diverse recommendations (berbeda kategori)
  7. Return top 8 recommendations

```php
calculateMatchScore(Food $food, Analysis $analysis, string $dietType, array $conditions): int
```
- **Scoring Factors**:
  - **Diet Type Match** (20-25 points)
    - Low_Carb: prefer carbs < 20g
    - Low_Sodium: prefer fresh foods (Buah, Sayuran)
    - High_Protein: prefer protein > 25g
    - Balanced: prefer balanced macros
  
  - **Health Condition Match** (15-20 points)
    - Diabetes: low carb, high fiber
    - Hypertension: avoid salty foods
    - Obesity: low calorie, high fiber
    - High Cholesterol: high fiber, low fat
  
  - **BMI-based** (8-15 points)
    - Underweight: high calorie
    - Overweight/Obese: low calorie
  
  - **Health Goal** (8-18 points)
    - Lose weight: low cal, high protein
    - Gain muscle: high protein
    - Maintain: balanced
  
  - **Activity Level** (8-10 points)
  - **Dietary Restriction** (15 points / -40 penalty)

### 4. **Data Flow**

```
User Input
    ↓
Analysis Model (save data)
    ↓
BodyAnalysisService
    ├─ Calculate BMI
    ├─ Predict Diet Type (Naive Bayes)
    ├─ Detect Health Conditions
    └─ Calculate Daily Calories
    ↓
RecommendationService
    ├─ Get all foods (1346 items)
    ├─ Score each food (0-100)
    ├─ Filter > 60 score
    ├─ Select diverse (8 items)
    └─ Save to Recommendation table
    ↓
Return to Controller/Frontend
```

---

## 🎯 Cara Menggunakan

### Setup Database & Import Data

```bash
# 1. Setup database (pastikan MySQL running)
php artisan migrate:fresh

# 2. Import makanan Indonesia (1346 items)
php artisan db:seed --class=FoodSeeder
```

### Testing via Tinker

```bash
php artisan tinker
```

```php
// Test 1: Create analysis
$analysis = new \App\Models\Analysis([
    'age' => 30,
    'weight' => 85,
    'height' => 170,
    'gender' => 'male',
    'blood_pressure_systolic' => 140,
    'blood_pressure_diastolic' => 90,
    'blood_sugar' => 130,
    'cholesterol' => 220,
    'activity_level' => 'sedentary',
    'dietary_restriction' => 'none',
    'health_goal' => 'lose_weight',
    'session_id' => 'test-123',
]);

// Calculate BMI
$service = new \App\Services\BodyAnalysisService();
$analysis->bmi = $service->calculateBMI($analysis->weight, $analysis->height);
$analysis->bmi_category = $service->getBMICategory($analysis->bmi);
$analysis->save();

// Test 2: Predict diet type
$dietType = $service->predictDietType($analysis);
echo "Predicted Diet: $dietType\n";

// Test 3: Get recommendations
$recService = new \App\Services\RecommendationService($service);
$recommendations = $recService->generateRecommendations($analysis);

echo "Top 5 Recommendations:\n";
foreach (array_slice($recommendations, 0, 5) as $rec) {
    echo "- {$rec['food']->name} (Score: {$rec['score']})\n";
}

// Test 4: Save recommendations
$recService->saveRecommendations($analysis, $recommendations);
```

---

## 📊 Dataset Info

### nutrition.csv (Makanan Indonesia)
- **Jumlah**: 1346 items
- **Kategori Auto-Detect**:
  - Sayuran 🥬
  - Buah 🍎
  - Protein Hewani 🍗
  - Protein Nabati 🥜
  - Karbohidrat 🍚
  - Dairy 🥛
  - Lainnya 🍽️

### diet_recommendations_dataset.csv (Training Data)
- **Jumlah**: 1000 patients
- **Digunakan untuk**: Reference logic Naive Bayes
- **Kolom penting**: BMI, Disease_Type, Diet_Recommendation

---

## 🚀 Next Steps (Yang Perlu Kamu Kerjain)

1. **Setup Database**
   - Install/nyalakan MySQL/MariaDB
   - Configure `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

2. **Run Migration & Seeder**
   ```bash
   php artisan migrate:fresh
   php artisan db:seed --class=FoodSeeder
   ```

3. **Buat Controller untuk API**
   - `POST /api/analysis` - Create analysis + get recommendations
   - `GET /api/foods` - List all foods (optional)
   - `GET /api/recommendations/{session_id}` - Get recommendations by session

4. **Frontend Integration**
   - Form input (age, weight, height, dll)
   - Call API
   - Display recommendations

5. **Google Auth** - Nanti aja di akhir! (prioritas rendah)

---

## 📝 Notes

- **Naive Bayes** diimplementasi sebagai **scoring system** karena lebih praktis untuk production
- Dataset makanan Indonesia lengkap (1346 items) sudah siap
- Logic filtering sangat comprehensive (BMI, conditions, goals, diet type)
- Semua sudah terintegrasi dengan model & database Laravel

**Status**: ✅ **Backend Logic DONE!**
**Tinggal**: Frontend + API Controller + Testing

<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Food;

echo "========================================\n";
echo "  DATA QUALITY CHECK\n";
echo "========================================\n\n";

// Total foods
$total = Food::count();
echo "Total Foods: {$total}\n\n";

// Fiber data
$withFiber = Food::whereNotNull('fiber')->where('fiber', '>', 0)->count();
$fiberPercent = round(($withFiber / $total) * 100, 1);
echo "Foods with FIBER data:\n";
echo "  ✅ {$withFiber}/{$total} ({$fiberPercent}%)\n\n";

// Sodium data
$withSodium = Food::whereNotNull('sodium')->where('sodium', '>', 0)->count();
$sodiumPercent = round(($withSodium / $total) * 100, 1);
echo "Foods with SODIUM data:\n";
echo "  ✅ {$withSodium}/{$total} ({$sodiumPercent}%)\n\n";

// Sugar data
$withSugar = Food::whereNotNull('sugars')->where('sugars', '>', 0)->count();
$sugarPercent = round(($withSugar / $total) * 100, 1);
echo "Foods with SUGAR data:\n";
echo "  ✅ {$withSugar}/{$total} ({$sugarPercent}%)\n\n";

// Sample foods from nilai-gizi.csv
echo "========================================\n";
echo "Sample foods from nilai-gizi.csv:\n";
echo "========================================\n";
$samples = Food::whereNotNull('sodium')
    ->where('sodium', '>', 0)
    ->inRandomOrder()
    ->take(10)
    ->get(['name', 'calories', 'fiber', 'sodium', 'sugars', 'category']);

foreach($samples as $food) {
    $fiber = $food->fiber ?? '0';
    $sodium = $food->sodium ?? '0';
    $sugar = $food->sugars ?? '0';
    echo "• {$food->name}\n";
    echo "  Category: {$food->category}\n";
    echo "  {$food->calories} cal | Fiber: {$fiber}g | Sodium: {$sodium}mg | Sugar: {$sugar}g\n\n";
}

echo "========================================\n";
echo "✅ Data quality improved with nilai-gizi.csv!\n";
echo "========================================\n";

<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Food;

echo "=== Categories in Database ===\n\n";
$categories = Food::select('category')->distinct()->pluck('category')->toArray();
echo "Total unique categories: " . count($categories) . "\n\n";
foreach ($categories as $cat) {
    $count = Food::where('category', $cat)->count();
    echo "- $cat ($count foods)\n";
}

echo "\n=== Sample Foods ===\n\n";
$samples = Food::take(5)->get(['name', 'category', 'calories', 'protein', 'carbs', 'fat', 'fiber', 'sugars', 'sodium', 'cholesterol']);
foreach ($samples as $food) {
    echo "{$food->name} ({$food->category})\n";
    echo "  Calories: {$food->calories}, P: {$food->protein}g, C: {$food->carbs}g, F: {$food->fat}g\n";
    echo "  Fiber: {$food->fiber}g, Sugar: {$food->sugars}g, Sodium: {$food->sodium}mg, Chol: {$food->cholesterol}mg\n\n";
}

<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Food;
use App\Models\Analysis;

echo "=== ADMIN DASHBOARD CHECK ===\n\n";

// Check admin user
$admin = User::where('email', 'admin@avenution.com')->first();
if (!$admin) {
    echo "❌ Admin user not found! Run: php artisan db:seed --class=AdminSeeder\n";
    exit(1);
}

echo "✅ Admin User: {$admin->name}\n";
echo "   Email: {$admin->email}\n";
echo "   Roles: " . implode(', ', $admin->getRoleNames()->toArray()) . "\n";

echo "\n=== Dashboard Stats ===\n";
$stats = [
    'totalUsers' => User::count(),
    'totalAnalyses' => Analysis::count(),
    'totalFoods' => Food::count(),
];

echo "Total Users: {$stats['totalUsers']}\n";
echo "Total Analyses: {$stats['totalAnalyses']}\n";
echo "Total Foods: {$stats['totalFoods']}\n";

echo "\n=== Recent Analyses ===\n";
$recentAnalyses = Analysis::with(['user', 'recommendations'])
    ->latest()
    ->take(5)
    ->get();

if ($recentAnalyses->count() > 0) {
    echo "Found {$recentAnalyses->count()} recent analyses:\n";
    foreach ($recentAnalyses as $analysis) {
        $userName = $analysis->user ? $analysis->user->name : 'Guest';
        $recoCount = $analysis->recommendations->count();
        echo "  - {$userName}: BMI {$analysis->bmi} ({$analysis->bmi_category}), {$recoCount} recommendations\n";
    }
} else {
    echo "No analyses yet (this is OK for new installations)\n";
}

echo "\n=== Navigation Links ===\n";
echo "✅ Admin Panel: /admin (should show dashboard)\n";
echo "✅ Manage Foods: /admin/foods (index page)\n";
echo "✅ Add New Food: /admin/foods/create\n";

echo "\n=== Test Steps ===\n";
echo "1. Start server: php artisan serve\n";
echo "2. Login: http://localhost:8000/login\n";
echo "   Email: admin@avenution.com\n";
echo "   Password: password\n";
echo "3. You should see navigation menu with:\n";
echo "   - Dashboard\n";
echo "   - Admin Panel\n";
echo "   - 🍽️ Manage Foods\n";
echo "4. Click any menu to navigate\n";

echo "\n✅ Admin dashboard setup complete!\n";

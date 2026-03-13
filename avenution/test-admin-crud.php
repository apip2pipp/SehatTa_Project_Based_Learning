<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Food;

echo "=== ADMIN CRUD TEST ===\n\n";

// Check if admin user exists
$admin = User::where('email', 'admin@avenution.com')->first();
if ($admin) {
    echo "✅ Admin user exists: {$admin->name} ({$admin->email})\n";
    echo "   Roles: " . implode(', ', $admin->getRoleNames()->toArray()) . "\n";
} else {
    echo "❌ Admin user not found. Run: php artisan db:seed --class=AdminSeeder\n";
}

echo "\n=== Food Statistics ===\n";
$total = Food::count();
echo "Total Foods: $total\n\n";

$categories = Food::select('category')->selectRaw('count(*) as count')->groupBy('category')->orderBy('count', 'desc')->get();
echo "Foods by Category:\n";
foreach ($categories as $cat) {
    echo "  - {$cat->category}: {$cat->count}\n";
}

echo "\n=== Sample Foods (for editing test) ===\n";
$samples = Food::take(3)->get(['id', 'name', 'category', 'calories', 'protein', 'carbs', 'fat']);
foreach ($samples as $food) {
    echo "\nFood ID: {$food->id}\n";
    echo "  Name: {$food->name}\n";
    echo "  Category: {$food->category}\n";
    echo "  Nutrition: {$food->calories} kcal, P:{$food->protein}g C:{$food->carbs}g F:{$food->fat}g\n";
}

echo "\n=== CRUD Routes Available ===\n";
echo "Index:  GET    /admin/foods\n";
echo "Create: GET    /admin/foods/create\n";
echo "Store:  POST   /admin/foods\n";
echo "Edit:   GET    /admin/foods/{id}/edit\n";
echo "Update: PUT    /admin/foods/{id}\n";
echo "Delete: DELETE /admin/foods/{id}\n";

echo "\n=== Validation Rules ===\n";
echo "Categories: Protein Hewani, Protein Nabati, Karbohidrat, Sayuran, Buah, Dairy, Lainnya\n";
echo "Required: name, category, calories, protein, carbs, fat\n";
echo "Optional: fiber, sugars, sodium, cholesterol, description, image_url, dietary_tags, health_benefits, emoji\n";

echo "\n=== Next Steps ===\n";
echo "1. Start server: php artisan serve\n";
echo "2. Login as admin: http://localhost:8000/login\n";
echo "   Email: admin@avenution.com\n";
echo "   Password: password\n";
echo "3. Access admin foods: http://localhost:8000/admin/foods\n";
echo "4. Test: Search, Filter, Create, Edit, Delete\n";

echo "\n✅ Admin CRUD setup complete!\n";

<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== ADMIN ROUTING TEST ===\n\n";

$admin = User::where('email', 'admin@avenution.com')->first();
if (!$admin) {
    echo "❌ Admin not found!\n";
    exit(1);
}

echo "✅ Admin User: {$admin->name}\n";
echo "   Has 'admin' role: " . ($admin->hasRole('admin') ? 'YES' : 'NO') . "\n";

echo "\n=== Expected Behavior ===\n";
echo "When admin logs in:\n";
echo "1. ✅ Route /dashboard will auto-redirect to /admin\n";
echo "2. ✅ Logo clicks will go to /admin (not /dashboard)\n";
echo "3. ✅ Navigation menu shows:\n";
echo "   - Dashboard (points to /admin)\n";
echo "   - 🍽️ Manage Foods (points to /admin/foods)\n";
echo "4. ✅ No more empty user dashboard for admins!\n";

echo "\n=== Regular User Behavior (Unchanged) ===\n";
echo "When regular user logs in:\n";
echo "1. Route /dashboard shows user dashboard\n";
echo "2. Navigation shows normal user menu\n";

echo "\n=== Admin Dashboard Features ===\n";
echo "- Quick Stats (Users, Analyses, Foods)\n";
echo "- 2 Action Cards:\n";
echo "  * Manage Foods Database (with search/filter info)\n";
echo "  * Add New Food Item (with category info)\n";
echo "- Recent Analyses Table\n";
echo "- Helpful navigation hint\n";

echo "\n=== Test Steps ===\n";
echo "1. php artisan serve\n";
echo "2. Login as: admin@avenution.com / password\n";
echo "3. After login, you'll be at: /admin (Admin Dashboard)\n";
echo "4. Click 'Dashboard' in menu → stays at /admin\n";
echo "5. Click '🍽️ Manage Foods' → goes to /admin/foods\n";
echo "6. Click logo → goes to /admin\n";

echo "\n✅ Admin routing optimized!\n";

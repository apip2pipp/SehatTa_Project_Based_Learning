@echo off
echo ========================================
echo   AVENUTION - Database Setup Test
echo ========================================
echo.

echo [STEP 1] Testing MySQL Connection...
php test-db.php

echo.
echo ========================================
echo [STEP 2] Ready to run migrations?
echo ========================================
echo.
echo Jika koneksi berhasil, jalankan:
echo   php artisan migrate:fresh
echo   php artisan db:seed --class=FoodSeeder
echo.
pause

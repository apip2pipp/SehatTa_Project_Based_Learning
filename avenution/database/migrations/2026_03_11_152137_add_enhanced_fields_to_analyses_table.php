<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('analyses', function (Blueprint $table) {
            // Make session_id nullable (we use user_id instead)
            $table->string('session_id')->nullable()->change();
            
            // Make some health metrics nullable (not everyone has them)
            $table->integer('blood_pressure_systolic')->nullable()->change();
            $table->integer('blood_pressure_diastolic')->nullable()->change();
            $table->integer('blood_sugar')->nullable()->change();
            $table->integer('cholesterol')->nullable()->change();
            $table->string('dietary_restriction')->nullable()->change();
            
            // Add new fields for enhanced functionality
            $table->string('goal')->nullable()->after('activity_level'); // weight_loss, maintain, muscle_gain
            $table->string('predicted_diet_type')->nullable()->after('bmi_category'); // Balanced, Low_Carb, Low_Sodium, High_Protein
            $table->json('health_conditions')->nullable()->after('predicted_diet_type'); // Array of detected conditions
            $table->integer('daily_calorie_target')->nullable()->after('health_conditions'); // Calculated daily calories
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analyses', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['goal', 'predicted_diet_type', 'health_conditions', 'daily_calorie_target']);
            
            // Revert nullable changes (make required again)
            $table->string('session_id')->nullable(false)->change();
            $table->integer('blood_pressure_systolic')->nullable(false)->change();
            $table->integer('blood_pressure_diastolic')->nullable(false)->change();
            $table->integer('blood_sugar')->nullable(false)->change();
            $table->integer('cholesterol')->nullable(false)->change();
            $table->string('dietary_restriction')->nullable(false)->change();
        });
    }
};

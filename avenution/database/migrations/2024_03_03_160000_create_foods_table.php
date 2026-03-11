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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable(); // breakfast, lunch, dinner, snack
            $table->integer('calories')->default(0);
            $table->decimal('protein', 5, 2)->default(0);
            $table->decimal('carbs', 5, 2)->default(0);
            $table->decimal('fat', 5, 2)->default(0);
            $table->decimal('fiber', 5, 2)->default(0)->nullable();
            $table->decimal('sugars', 5, 2)->default(0)->nullable();
            $table->decimal('sodium', 8, 2)->default(0)->nullable(); // mg
            $table->decimal('cholesterol', 8, 2)->default(0)->nullable(); // mg
            $table->string('meal_type')->nullable(); // breakfast, lunch, dinner, snack
            $table->text('description')->nullable();
            $table->text('image_url')->nullable(); // Changed from string to text for long URLs
            $table->json('dietary_tags')->nullable(); // vegetarian, vegan, gluten-free, etc.
            $table->json('health_benefits')->nullable(); // array of benefits
            $table->string('emoji')->default('🍽️');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};

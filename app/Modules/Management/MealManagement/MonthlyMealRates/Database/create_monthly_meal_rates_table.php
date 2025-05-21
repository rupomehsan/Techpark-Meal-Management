<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='/app/Modules/Management/MealManagement/MonthlyMealRates/Database/create_monthly_meal_rates_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_meal_rates', function (Blueprint $table) {
            $table->id();
            $table->date('month')->nullable();
            $table->double('meal_rate', 8, 2)->default(0); 
            $table->tinyInteger('is_visible')->default(1); 
            $table->date('month_start_date')->nullable();
            $table->date('month_end_date')->nullable();

            $table->bigInteger('creator')->unsigned()->nullable();
            $table->string('slug', 50)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_meals');
    }
};
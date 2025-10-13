<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='\App\Modules\Management\MealManagement\OffMeal\Database\create_off_meals_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('off_meals', function (Blueprint $table) {
            $table->id();
            $table->date('off_date')->nullable();
            $table->enum('meal_status', ['off', 'on'])->default('off');
            $table->text('description')->nullable();

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
        Schema::dropIfExists('off_meals');
    }
};

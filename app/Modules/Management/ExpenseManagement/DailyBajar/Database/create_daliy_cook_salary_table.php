<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='\App\Modules\Management\ExpenseManagement\DailyBajar\Database\create_daliy_cook_salary_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daliy_cook_salary', function (Blueprint $table) {
            $table->id();
            $table->double('cook_salary')->nullable();
            $table->date('salary_date')->nullable();

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
        Schema::dropIfExists('daliy_cook_salary');
    }
};

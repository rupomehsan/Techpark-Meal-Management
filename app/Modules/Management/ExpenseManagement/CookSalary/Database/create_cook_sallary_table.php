<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='\App\Modules\Management\ExpenseManagement\CookSalary\Database\create_cook_sallary_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cook_sallary', function (Blueprint $table) {
            $table->id();
            $table->string('month', 20)->nullable();
            $table->double('amount', 8,2)->nullable();
            $table->string('image', 100)->nullable();
            $table->enum('sallary_status', ['paid', 'unpaid'])->default('unpaid');
            $table->double('due_amount', 8,2)->nullable();

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
        Schema::dropIfExists('cook_sallary');
    }
};

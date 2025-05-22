<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     php artisan migrate --path='\App\Modules\Management\ExpenseManagement\DailyBajar\Database\create_daliy_bajars_table.php'
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daliy_bajars', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('unit', 30)->nullable();
            $table->double('price', 8,2)->nullable();
            $table->double('total', 8,2)->nullable();
            $table->date('bajar_date')->nullable();

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
        Schema::dropIfExists('daliy_bajars');
    }
};

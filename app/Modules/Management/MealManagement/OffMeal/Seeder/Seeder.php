<?php

namespace App\Modules\Management\MealManagement\OffMeal\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\MealManagement\OffMeal\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\MealManagement\OffMeal\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 0; $i < 10; $i++) {
            self::$model::create([
                'off_date' => $faker->date,
                'meal_status' => 'off',
                'description' => $faker->paragraph()
            ]);
        }


    }
}
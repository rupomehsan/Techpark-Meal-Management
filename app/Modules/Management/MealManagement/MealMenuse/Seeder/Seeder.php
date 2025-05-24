<?php

namespace App\Modules\Management\MealManagement\MealMenuse\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="App\Modules\Management\MealManagement\MealMenuse\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\MealManagement\MealMenuse\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([
                'meal_date' => $faker->date(),
                'description' => $faker->sentence(5),
                'recipy' => $faker->paragraph(1),
            
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

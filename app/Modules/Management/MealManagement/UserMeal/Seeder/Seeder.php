<?php

namespace App\Modules\Management\MealManagement\UserMeal\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="App\Modules\Management\MealManagement\UserMeal\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\MealManagement\UserMeal\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([
                 'user_id' => $faker->numberBetween(1, 3),
                'quantity' => $faker->numberBetween(1, 5),
                'date' => $faker->date(),
                'meal_rate_id' => $faker->numberBetween(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

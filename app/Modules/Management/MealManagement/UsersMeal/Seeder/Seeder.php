<?php

namespace App\Modules\Management\MealManagement\UsersMeal\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\MealManagement\UsersMeal\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 10; $i++) {
            self::$model::create([
                // 'user_id' => 1,
                // 'quantity' => 2,
                'user_id' => $faker->numberBetween(1, 5),
                'quantity' => $faker->numberBetween(1, 3),
                'date' => $faker->date(),
                'meal_status' => $faker->randomElement(['on', 'off']),
                'meal_rate_id' => $faker->numberBetween(1, 3)
            ]);
        }
    }
}
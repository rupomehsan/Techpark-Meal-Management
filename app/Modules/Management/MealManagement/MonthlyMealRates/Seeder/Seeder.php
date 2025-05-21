<?php

namespace App\Modules\Management\MealManagement\MonthlyMealRates\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="App\Modules\Management\MealManagement\MonthlyMealRates\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\MealManagement\MonthlyMealRates\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([
                'month' => $faker->toDateString(),
                'meal_rate' => $faker->randomFloat(2, 35, 70),
                'is_visible' => $faker->boolean,
                'month_start_date' => $faker->toDateString(),
                'month_end_date' => $faker->toDateString(),
                'status' => $faker->numberBetween(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

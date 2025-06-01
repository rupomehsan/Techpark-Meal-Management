<?php

namespace App\Modules\Management\MealManagement\MonthlyMealRates\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\MealManagement\MonthlyMealRates\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\MealManagement\MonthlyMealRates\Models\Model::class;


    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 0; $i < 10; $i++) {
            $date = $faker->dateTimeThisYear();

            $startDate = $faker->dateTimeBetween('2021-01-01', '2025-01-01');
            $endDate = $faker->dateTimeBetween($startDate, '2025-12-31');

            self::$model::create([
                'month' => $date->format('F Y'),
                'meal_rate' => $faker->numberBetween(50, 60),
                'is_visible' => $faker->randomElement([0, 1]),
                'month_start_date' => $startDate->format('Y-m-d'),
                'month_end_date' => $endDate->format('Y-m-d'),
            ]);
        }
    }

}
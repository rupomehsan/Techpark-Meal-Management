<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

/**
 * User seeder management.
 */

use App\Modules\Management\UserManagement\Role\Seeder\Seeder as RoleSeeder;
use App\Modules\Management\UserManagement\User\Seeder\Seeder as UserSeeder;
use App\Modules\Management\SettingManagement\WebsiteSettings\Seeder\Seeder as WebsiteSettingsSeeder;

/**
 * Suppliyer seeder management.
 */
use App\Modules\Management\Blog\Seeder\Seeder as BlogCategorySeeder;

/**
 * Meal seeder management.
 */
use App\Modules\Management\MealManagement\UserMeal\Seeder\Seeder as UserMealsSeeder;
use App\Modules\Management\MealManagement\MealMenuse\Seeder\Seeder as MealMenusesSeeder;
use App\Modules\Management\MealManagement\MonthlyMealRates\Seeder\Seeder as MonthlyMealRatesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            /**
             * User seeder management.
             */
            RoleSeeder::class,
            UserSeeder::class,
            WebsiteSettingsSeeder::class,
            /**
             * Suppliyer seeder management.
             */
            BlogCategorySeeder::class,

            /**
             * Meal seeder management.
             */

            UserMealsSeeder::class,
            MealMenusesSeeder::class,
            MonthlyMealRatesSeeder::class,

        ]);
    }
}

<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

/**
 * User seeder management.
 */

    use App\Modules\Management\UserManagement\Role\Seeder\Seeder as RoleSeeder;
    use App\Modules\Management\UserManagement\User\Seeder\Seeder as UserSeeder;
    use App\Modules\Management\UserManagement\UserPayment\Seeder\Seeder as UserPaymentSeeder;
    use App\Modules\Management\SettingManagement\WebsiteSettings\Seeder\Seeder as WebsiteSettingsSeeder;

/**
 * DueListManagement seeder management.
 */
    use App\Modules\Management\MealManagement\UsersMeal\Seeder\Seeder as UsersMealSeeder;

/**
 * BatchManagement seeder management.
 */
    use App\Modules\Management\BatchManagement\Seeder\Seeder as BatchManagementSeeder;

/**
 * ExpenseManagement seeder management.
 */
    use App\Modules\Management\ExpenseManagement\DailyBajar\Seeder\Seeder as DailyBajarSeeder;
    use App\Modules\Management\ExpenseManagement\CookSalary\Seeder\Seeder as CookSalarySeeder;



/**
 * MealManagement seeder management.
 */
    use App\Modules\Management\DueListManagement\UserDueList\Seeder\Seeder as UserDueListSeeder;
    use App\Modules\Management\MealManagement\MealMenues\Seeder\Seeder as MealMenuSeeder;
    use App\Modules\Management\MealManagement\MonthlyMealRates\Seeder\Seeder as MonthlyMealRateSeeder;

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
            UserPaymentSeeder::class,
            WebsiteSettingsSeeder::class,

        /**
         * UserDueListSeeder management seeder management.
         */
            UserDueListSeeder::class,

        /**
         * DailyBajarSeeder management seeder management.
         */
            DailyBajarSeeder::class,
            CookSalarySeeder::class,
        /**
         * 
         * BatchManagement management seeder management.
         */
            BatchManagementSeeder::class,
            
            
        /**
         * Meal management seeder management.
         */
            UsersMealSeeder::class,
            MealMenuSeeder::class,
            MonthlyMealRateSeeder::class

        ]);
    }
}
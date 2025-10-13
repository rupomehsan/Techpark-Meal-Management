<?php

namespace App\Modules\Management\ExpenseManagement\CookSalary\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\ExpenseManagement\CookSalary\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\ExpenseManagement\CookSalary\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        self::$model::create([
            'month' => 'May',
            'amount' => 3000,
            'image' => 'avatar.png',
            'sallary_status' => 'unpaid',
            'due_amount' => 2000,
        ]);
        
    }
}

<?php

namespace App\Modules\Management\UserManagement\UserPayment\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="App\Modules\Management\UserManagement\UserPayment\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\UserManagement\UserPayment\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 10; $i++) {
            self::$model::create([                
                'user_id' => 1,
                'month' => 'May',
                'payment_date' => $faker->date(),
                'amount' => $faker->randomNumber
            ]);
        }
    }
}
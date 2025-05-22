<?php

namespace App\Modules\Management\BatchManagement\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\BatchManagement\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\BatchManagement\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();


        for ($i = 1; $i <= 10; $i++) {
            self::$model::create([                
                'department_id' => 1,
                'batch_name' => $faker->name,
            ]);
        }
    }
}
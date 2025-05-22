<?php

namespace App\Modules\Management\ExpenseManagement\DailyBajar\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\ExpenseManagement\DailyBajar\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        self::$model::create([
            'title' => 'Potato',
            'quantity' => 2,
            'unit' => 'kg',
            'price' => 30,
            'total' => 60,
            'bajar_date' => $faker->date,
        ]);

    }
}
<?php

namespace App\Modules\Management\DueListManagement\UserDueList\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\DueListManagement\UserDueList\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\DueListManagement\UserDueList\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        self::$model::create([
            'user_id' => 1,
            'month' => 'May',
            'amount' => 3000
        ]);
        

        
    }
}

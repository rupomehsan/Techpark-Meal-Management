<?php

namespace App\Modules\Management\UserManagement\Role\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\App\Modules\Management\UserManagement\Role\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\UserManagement\Role\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        self::$model::create([
            'name' => "Super Admin",
            'serial_no' => 1,
        ]);
        self::$model::create([
            'name' => "Admin",
            'serial_no' => 2,
        ]);
        self::$model::create([
            'name' => "Employee",
            'serial_no' => 3,
        ]);
        self::$model::create([
            'name' => "Student",
            'serial_no' => 4,
        ]);
        
    }
}
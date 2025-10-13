<?php 

namespace App\Modules\Management\MealManagement\UsersMeal\Actions;

class CheckOffMeal
{
    public static $OffMeal = \App\Modules\Management\MealManagement\OffMeal\Models\Model::class;

    public static function execute($date){
        try{
            $data = self::$OffMeal::where('off_date', $date)
                    ->where('meal_status', 'off')
                    ->first();
                    
            return entityResponse($data);

        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
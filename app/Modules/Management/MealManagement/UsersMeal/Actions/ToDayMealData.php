<?php 
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;

class ToDayMealData{

    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute($toDay){

        try{
            $data = self::$model::
                with('user')
                ->where('date', $toDay)
                ->where('meal_status', 'on')
                ->get();
                // ->sum('quantity');
                
            return entityResponse($data);
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }

    }

}
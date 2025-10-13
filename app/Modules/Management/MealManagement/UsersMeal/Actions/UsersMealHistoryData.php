<?php 
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;
use Carbon\Carbon;

class UsersMealHistoryData{

    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute($userId, $date){
        dd('ok');


        try {

            $data = self::$model::query()
                ->where('user_id', $userId)
                ->where('date', $date)
                ->sum('quantity');


            return entityResponse($data);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }

    }
    
}
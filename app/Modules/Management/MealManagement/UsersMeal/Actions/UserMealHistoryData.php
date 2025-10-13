<?php 
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;
use Carbon\Carbon;

class UserMealHistoryData{

    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute($uid, $date){
            
        try {

            $data = self::$model::query()
                ->where('user_id', $uid)
                ->where('date', $date)
                ->sum('quantity');

            return entityResponse($data);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }

    }
}
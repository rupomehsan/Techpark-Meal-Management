<?php 

namespace App\Modules\Management\MealManagement\MonthlyMealRates\Actions;

class DestroyData
{
    static $model = \App\Modules\Management\MealManagement\MonthlyMealRates\Models\Model::class;

    public static function execute($slug){
        try{
            if(!$data = self::$model::where('slug', request()->slug)->first()){
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $data->forceDelete();
            return messageResponse('Data Deleted Successful...', [], 201, 'success');
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
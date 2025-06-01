<?php 

namespace App\Modules\Management\MealManagement\MonthlyMealRates\Actions;

class StoreData
{
    static $model = \App\Modules\Management\MealManagement\MonthlyMealRates\Models\Model::class;

    public static function execute($request){
        try {
            $requestData = $request->validated();
            if($data = self::$model::query()->create($requestData)){
                return messageResponse('Data Added Successful...', $data, 200, 'succcess');
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
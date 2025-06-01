<?php 

namespace App\Modules\Management\MealManagement\MealMenues\Actions;

class StoreData
{
    static $model = \App\Modules\Management\MealManagement\MealMenues\Models\Model::class;

    public static function execute($request){
        // dd($request->all());
        
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
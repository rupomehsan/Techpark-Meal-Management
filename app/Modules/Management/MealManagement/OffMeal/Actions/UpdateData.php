<?php 

namespace App\Modules\Management\MealManagement\OffMeal\Actions;

class UpdateData
{
    static $model = \App\Modules\Management\MealManagement\OffMeal\Models\Model::class;
     public static $userMeal     = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute($request, $slug){
        try{
            
            $requestData = $request->validated();

            if(!$data = self::$model::query()->where('slug', $slug)->first()){
                return messageResponse('Data not  found...', $data, 404, 'error');
            }

            // $data = self::$model::query()->where('slug', $slug)->first();
            // if ($data) {
            //     self::$userMeal::update('meal_status' => 'on');
            // }

            $data->update($requestData);
            return messageResponse('Data Updated Successful...', [], 201, 'success');
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
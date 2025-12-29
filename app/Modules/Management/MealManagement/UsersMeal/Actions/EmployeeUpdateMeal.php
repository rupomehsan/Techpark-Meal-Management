<?php 
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;
use Auth;

class EmployeeUpdateMeal 
{
    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

        public static function execute($request, $slug)
        {
            try {
                // dd('all request', $request->all());
                
                $requestData = $request->validated();
                
                if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                    return messageResponse('Data not found...',$data, 404, 'error');
                }
                // dd($data);
                $data->update($requestData);
                return messageResponse('Item updated successfully',$data, 201);
            } catch (\Exception $e) {
                return messageResponse($e->getMessage(),[], 500, 'server_error');
            }
        }


        


        
}
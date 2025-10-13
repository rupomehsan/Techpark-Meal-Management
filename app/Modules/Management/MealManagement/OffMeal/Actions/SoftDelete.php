<?php 

namespace App\Modules\Management\MealManagement\OffMeal\Actions;

class  SoftDelete
{
    static $model = \App\Modules\Management\MealManagement\OffMeal\Models\Model::class;

    public static function execute(){
        try{
            if(!$data = self::$model::where('slug', request()->slug)->first()){
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $data->delete();
            return messageResponse('Data Successfully soft deleted', [], 201, 'success');
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
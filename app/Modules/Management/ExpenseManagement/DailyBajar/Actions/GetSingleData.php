<?php 

namespace App\Modules\Management\ExpenseManagement\DailyBajar\Actions;

class GetSingleData{
    static $model = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

    public static function execute($slug){
        try{
            $with = [];
            $fields = request()->input('fields') ?? ['*'];

            if(!$data = self::$model::query()->with($with)->select($fields)->where('slug', $slug)->first()){
                return messageResponse('Data not found...', $data, 404, 'erro');
            }
            return entityResponse($data);
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
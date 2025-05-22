<?php 

namespace App\Modules\Management\BatchManagement\Actions;

class SoftDelete
{

    static $model = \App\Modules\Management\BatchManagement\Models\Model::class;

    public static function execute(){
        try{
            if(!$data = self::$model::where('slug', request()->slug)->first()){
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $data->delete();
            return messageResponse('Data Duccessfully Soft Deleted...', [], 201, 'success');
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
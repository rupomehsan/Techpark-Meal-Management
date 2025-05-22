<?php 

namespace App\Modules\Management\DueListManagement\UserDueList\Actions;

class DestroyData
{
    static $model = \App\Modules\Management\DueListManagement\UserDueList\Models\Model::class;


    public static function execute($slug){
        try{
            if(!$data = self::$model::where('slug', $slug)->first()){
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $data->forceDelete();
            return messageResponse('Data successfully deleted...', [], 201, 'success');
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
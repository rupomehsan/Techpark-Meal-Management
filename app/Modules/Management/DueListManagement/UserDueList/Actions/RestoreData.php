<?php 

namespace App\Modules\Management\DueListManagement\UserDueList\Actions;

class RestoreData
{
    static $model = \App\Modules\Management\DueListManagement\UserDueList\Models\Model::class;

    public static function execute(){
        try{
            if(!$data = self::$model::onlyTrashed()->where('slug', request()->slug)->first()){
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            $data->restore();
            return messageResponse('Data successfully destroy...', [], 201, 'success');
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
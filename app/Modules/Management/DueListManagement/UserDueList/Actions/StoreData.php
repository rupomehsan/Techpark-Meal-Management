<?php 
namespace App\Modules\Management\DueListManagement\UserDueList\Actions;

class StoreData 
{
    static $model = \App\Modules\Management\DueListManagement\UserDueList\Models\Model::class;

    public static function execute($request){
        // dd($request->all());
        try{
            $requestData = $request->validated();

            if($data = self::$model::query()->create($requestData)){
                return messageResponse('Data added successful..', $data, 200, 'success');
            }
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
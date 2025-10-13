<?php 
namespace App\Modules\Management\ExpenseManagement\CookSalary\Actions;

class StoreData 
{
    static $model = \App\Modules\Management\ExpenseManagement\CookSalary\Models\Model::class;

    public static function execute($request){
        // dd($request->all());
        try{
            $requestData = $request->validated();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $requestData['image'] = uploader($image, 'uploads/users');
            }

            if($data = self::$model::query()->create($requestData)){
                return messageResponse('Data added successful..', $data, 200, 'success');
            }
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
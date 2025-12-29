<?php 
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;
use Auth;

class EmployeeStoreMeal 
{
    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute($request, $userId){
        try{
            // dd($request);
            $requestData = $request->validated();
            // $requestData['user_id'] = Auth::user()->id;

            if ($userId && isset($requestData['user_id'])) {
                $requestData['user_id'] = $userId;
            }

            $userId = $requestData['user_id'];
            $date   = $requestData['date'];
            $qty    = $requestData['quantity'];

            $data = self::$model::where('user_id', $userId)
                ->whereDate('date', $date)
                ->first();

            if ($data) {
                $data->quantity += $qty;
                $data->save();

                return messageResponse('Meal updated successfully', $data, 200, 'success');
            }else {
                // Create new meal
                $data = self::$model::create($requestData);

                return messageResponse('Meal added successfully', $data, 201, 'success');
            }

            // if($data = self::$model::query()->create($requestData)){
            //     return messageResponse('Data added successful..', $data, 200, 'success');
            // }
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
<?php 
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;

class StoreData{

    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $offMealModel = \App\Modules\Management\MealManagement\OffMeal\Models\Model::class;

    public static function execute($request){

        try {
            $requestData = $request->validated();
            $userId = $requestData['user_id'];
            $date   = $requestData['date'];
            $qty    = $requestData['quantity'];


            // $isMealOff = self::$offMealModel::
            //         whereDate('off_date', $date)
            //         ->exists();

            // if ($isMealOff) {
            //     return messageResponse("Meal entry not allowed. Meal is OFF for this date.", [], 403, 'forbidden');
            // }


            $data = self::$model::where('user_id', $userId)
                ->whereDate('date', $date)
                ->first();

            if ($data) {
                $data->quantity += $qty;
                $data->save();

                return messageResponse('Meal updated successfully', $data, 200, 'success');
            } else {
                // Create new meal
                $data = self::$model::create($requestData);

                return messageResponse('Meal added successfully', $data, 201, 'success');
            }

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }

    
    }


}
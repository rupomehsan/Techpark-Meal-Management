<?php
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;

class UserStoreData
{

    static $model        = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $offMealModel = \App\Modules\Management\MealManagement\OffMeal\Models\Model::class;

    public static function execute($request, $userId)
    {

        try {
            $requestData = $request->validated();

            if ($userId && isset($requestData['user_id'])) {
                $requestData['user_id'] = $userId;
            }
            // dd('ok', $requestData);
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

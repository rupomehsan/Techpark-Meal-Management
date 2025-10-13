<?php
namespace App\Modules\Management\MealManagement\OffMeal\Actions;

use Illuminate\Support\Facades\DB;

class StoreData
{
    public static $model = \App\Modules\Management\MealManagement\OffMeal\Models\Model::class;
    public static $userMeal     = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute($request)
    {

        try {
            $requestData = $request->validated();

            $data = self::$model::query()->create($requestData);

            if ($data) {
                self::$userMeal::where('date', $request->off_date)
                    ->update([
                        'quantity' => 0,
                        'meal_status' => 'off',
                    ]);

                return messageResponse('Data Added Successfully...', $data, 200, 'success');
            } else {
                return messageResponse('Failed to add Off Meal data.', [], 400, 'error');
            }

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}

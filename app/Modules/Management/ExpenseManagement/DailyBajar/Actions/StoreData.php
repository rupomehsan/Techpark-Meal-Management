<?php
namespace App\Modules\Management\ExpenseManagement\DailyBajar\Actions;
use Illuminate\Support\Facades\DB;

class StoreData
{
    static $model = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();
            $count       = count($requestData['title']);
            $savedData   = [];

            for ($i = 0; $i < $count; $i++) {
                $row = [
                    'title'       => $requestData['title'][$i],
                    'quantity'    => $requestData['quantity'][$i],
                    'unit'        => $requestData['unit'][$i],
                    'price'       => $requestData['price'][$i],
                    'total'       => $requestData['total'][$i],
                    'bajar_date'  => $requestData['bajar_date'],
                ];

                $savedData[] = self::$model::create($row);

            }
            
            return messageResponse('Data Added Successfully', $savedData, 200, 'success');

            DB::table('daliy_cook_salary')->insert([
                'salary_date' => $request->salary_date,
                'cook_salary' => $request->cook_salary
            ]);


        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }

}

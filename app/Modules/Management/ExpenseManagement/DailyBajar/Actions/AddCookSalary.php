<?php
namespace App\Modules\Management\ExpenseManagement\DailyBajar\Actions;
use Illuminate\Support\Facades\DB;

class AddCookSalary
{
    // static $model = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

    public static function execute($request)
    {
        try {

            $requestData = $request->validated();
           
            $savedData = DB::table('daliy_cook_salary')->insert([
                'salary_date' => $request->salary_date,
                'cook_salary' => $request->cook_salary
            ]);
            
            return messageResponse('Data Added Successfully', $savedData, 200, 'success');


        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }

}

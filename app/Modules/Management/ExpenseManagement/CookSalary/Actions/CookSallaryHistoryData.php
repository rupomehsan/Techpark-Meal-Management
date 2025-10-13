<?php 
namespace App\Modules\Management\ExpenseManagement\CookSalary\Actions;
use DB;

class CookSallaryHistoryData
{
    static $model = \App\Modules\Management\ExpenseManagement\CookSalary\Models\Model::class;

    public static function execute($month){
        try{

            if (!$month) {
                return messageResponse('Month is required.', [], 422, 'validation_error');
            }

            $data = self::$model::query()
                ->where('month', $month)
                ->get();

            if ($data->isEmpty()) {
                return messageResponse('No salary history found for this month.', [], 404, 'not_found');
            }

            return entityResponse($data);
        }catch(\Exception $e){
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }

     
}
<?php
namespace App\Modules\Management\ExpenseManagement\CookSalary\Actions;

use Carbon\Carbon;
use DB;

class CookSallaryByDailyBajar
{
    static $model = \App\Modules\Management\ExpenseManagement\CookSalary\Models\Model::class;

    public static function execute()
    {
        // debug removed

        try {
            $currentMonth = Carbon::now()->month;
            $currentYear  = Carbon::now()->year;

            $totalCookSalary = DB::table('daliy_cook_salary')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('cook_salary');

            return messageResponse('Current month cook salary calculated successfully',
                [
                    'month'             => Carbon::now()->format('F Y'),
                    'total_cook_salary' => $totalCookSalary,
                ],
                200,
                'success'
            );

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }

    }

}

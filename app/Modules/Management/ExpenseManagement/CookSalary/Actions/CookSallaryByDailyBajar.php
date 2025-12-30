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
            // dd('inside cook salary by daily bajar');
            $currentMonth = Carbon::now()->month;
            $currentYear  = Carbon::now()->year;

            $totalCookSalary = DB::table('daliy_cook_salary')->whereMonth('salary_date', $currentMonth)
                            ->whereYear('salary_date', $currentYear)
                            ->sum('cook_salary');
            // dd('total cook salary', $totalCookSalary);

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

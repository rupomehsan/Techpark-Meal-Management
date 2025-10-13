<?php
namespace App\Modules\Management\ReportManagement\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use DB;

class MonthlyDetailsInvoice {

    public static function execute(){;

        try {
            $month = request()->input('month', date('Y-m'));
            
            $cookSalaryGrouped = DB::table('daliy_cook_salary')
                ->select(
                    'salary_date',
                    DB::raw('SUM(cook_salary) as cook_salary_sum')
                )
                ->groupBy('salary_date');

            $dailySummary = DB::table('daliy_bajars as b')
                ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
                    $join->on('c.salary_date', '=', 'b.bajar_date');
                })
                ->whereRaw("DATE_FORMAT(b.bajar_date, '%Y-%m') = ?", [$month])
                ->select(
                    'b.bajar_date',
                    DB::raw('SUM(b.total) as total_bajar_cost'),
                    DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary'),
                    DB::raw('(SUM(b.total) + IFNULL(c.cook_salary_sum, 0)) as grand_total')
                )
                ->groupBy('b.bajar_date', 'c.cook_salary_sum')
                ->orderBy('b.bajar_date', 'desc')
                ->get();

            $monthlyTotal = [
                'monthly_total_bajar_cost' => $dailySummary->sum('total_bajar_cost'),
                'monthly_total_cook_salary' => $dailySummary->sum('cook_salary'),
                'monthly_grand_total' => $dailySummary->sum('grand_total'),
            ];

            $details = DB::table('daliy_bajars as b')
                ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
                    $join->on('c.salary_date', '=', 'b.bajar_date');
                })
                ->whereRaw("DATE_FORMAT(b.bajar_date, '%Y-%m') = ?", [$month])
                ->select(
                    'b.bajar_date',
                    'b.title',
                    'b.quantity',
                    'b.unit',
                    'b.price',
                    'b.total as bajar_total',
                    DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary')
                )
                ->orderBy('b.bajar_date', 'desc')
                ->get();

            return response()->json([
                'daily_summary' => $dailySummary,
                'monthly_total' => $monthlyTotal,
                'details' => $details,
            ]);

            return [
                'daily_summary' => $dailySummary,
                'monthly_total' => $monthlyTotal,
                'details' => $details,
            ];
            
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        } 
        return [
            'month'   => $month,
            'details' => $details
        ];

        // dd('invoice called...');

        // try {


        //     // Daily summary
        //     $dailySummary = DB::table('daily_meal_reports')
        //         ->select(
        //             'date',
        //             DB::raw('SUM(meal_qty) as total_meal_qty'),
        //             DB::raw('SUM(meal_cost) as total_meal_cost')
        //         )
        //         ->where('date', 'like', "$month%")
        //         ->groupBy('date')
        //         ->get();

        //     // Monthly total
        //     $monthlyTotal = [
        //         'total_meal_qty' => $dailySummary->sum('total_meal_qty'),
        //         'total_meal_cost' => $dailySummary->sum('total_meal_cost'),
        //     ];

        //     // Details (optional)
        //     $details = DB::table('daily_meal_reports')
        //         ->where('date', 'like', "$month%")
        //         ->get();

        //     return [
        //         'daily_summary' => $dailySummary,
        //         'monthly_total' => $monthlyTotal,
        //         'details' => $details,
        //     ];
                
        // } catch (\Exception $e) {
        //     return messageResponse($e->getMessage(), [], 500, 'server_error');
        // }
        // return entityResponse($data);
        
    }
    
    
    
}




?>


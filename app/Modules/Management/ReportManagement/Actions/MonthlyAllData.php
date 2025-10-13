<?php

namespace App\Modules\Management\ReportManagement\Actions;
use DB;

class MonthlyAllData
{
    static $model = \App\Modules\Management\ReportManagement\Models\Model::class;

    public static function execute()
    {
        try {

                $start_month = request()->input('start_month');
                $end_month = request()->input('end_month');

                $meals = DB::table('users_meals')
                    ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(quantity) as total_meals")
                    ->where('meal_status', 'on')
                    ->groupBy('month')
                    ->get()
                    ->keyBy('month');

                $cooks = DB::table('daliy_cook_salary')
                    ->selectRaw("DATE_FORMAT(salary_date, '%Y-%m') as month, SUM(cook_salary) as total_salary")
                    ->groupBy('month')
                    ->get()
                    ->keyBy('month');

                $costs = DB::table('daliy_bajars')
                    ->selectRaw("DATE_FORMAT(bajar_date, '%Y-%m') as month, SUM(total) as total_cost")
                    ->groupBy('month')
                    ->get()
                    ->keyBy('month');

                $report = [];

                $months = collect($meals->keys())
                    ->merge($costs->keys())
                    ->merge($cooks->keys())
                    ->unique()
                    ->sortDesc(); 

                foreach ($months as $month) {
                    $mealQty    = $meals[$month]->total_meals ?? 0;
                    $totalCost  = $costs[$month]->total_cost ?? 0;
                    $cookSalary = $cooks[$month]->total_salary ?? 0;
                    $grandTotal = $totalCost + $cookSalary;

                    $mealRate = ($mealQty > 0) ? round($grandTotal / $mealQty, 2) : 0;

                    $report[] = [
                        'month'        => $month, 
                        'meal_qty'     => $mealQty,
                        'total_cost'   => $totalCost,
                        'cook_salary'  => $cookSalary,
                        'grand_total'  => $grandTotal,
                        'meal_rate'    => $mealRate,
                    ];
                }

                if ($start_month && $end_month) {
                    $startMonth = date('Y-m', strtotime($start_month));
                    $endMonth   = date('Y-m', strtotime($end_month));

                    $report = collect($report)->filter(function ($item) use ($startMonth, $endMonth) {
                        return $item['month'] >= $startMonth && $item['month'] <= $endMonth;
                    })->values()->all();
                }

                return entityResponse(['data' => $report]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }

    }




}
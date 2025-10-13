<?php

namespace App\Modules\Management\ReportManagement\Actions;
use DB;

class DailyAllData
{
    static $model = \App\Modules\Management\ReportManagement\Models\Model::class;
    static $UsersMeal = \App\Modules\Management\MealMangement\UserMeal\Models\Model::class;

    // public static function execute()
    // {
    //     try {

    //         $start_date = request()->input('start_date');
    //         $end_date = request()->input('end_date');

    //         $meals = DB::table('users_meals')
    //             ->selectRaw('date, SUM(quantity) as total_meals')
    //             ->groupBy('date')
    //             ->get()
    //             ->keyBy('date');

    //         $cooks = DB::table('daliy_cook_salary')
    //             ->selectRaw('salary_date, SUM(cook_salary) as total_salary')
    //             ->groupBy('salary_date')
    //             ->get()
    //             ->keyBy('salary_date');

    //         $costs = DB::table('daliy_bajars')
    //             ->selectRaw('bajar_date, SUM(total) as total_cost')
    //             ->groupBy('bajar_date')
    //             ->get()
    //             ->keyBy('bajar_date');

    //         $report = [];

    //         $dates = collect($meals->keys())
    //             ->merge($costs->keys())
    //             ->merge($cooks->keys())
    //             ->unique()
    //             ->sortDesc(); 

    //         foreach ($dates as $date) {
    //             $mealQty    = $meals[$date]->total_meals ?? 0;
    //             $totalCost  = $costs[$date]->total_cost ?? 0;
    //             $cookSalary = $cooks[$date]->total_salary ?? 0;
    //             $grandTotal = $totalCost + $cookSalary;

    //             $mealRate = ($mealQty > 0) ? round($grandTotal / $mealQty, 2) : 0;

    //             $report[] = [
    //                 'date'         => $date,
    //                 'meal_qty'     => $mealQty,
    //                 'total_cost'   => $totalCost,
    //                 'cook_salary'  => $cookSalary,
    //                 'grand_total'  => $grandTotal,
    //                 'meal_rate'    => $mealRate,
    //             ];
    //         }
            
    //         if ($start_date && $end_date) {
    //             $report = collect($report)->filter(function ($item) use ($start_date, $end_date) {
    //                 return $item['date'] >= $start_date && $item['date'] <= $end_date;
    //             })->values()->all();
    //         }

    //         return entityResponse(['data' => $report]);

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }



    // public static function execute(){
    //     try {
    //         $today = date('Y-m-d');
    //         $tomorrow = date('Y-m-d', strtotime('+1 day'));

    //         $datesToConsider = [$today, $tomorrow];

    //         $mealQtySum = DB::table('users_meals')
    //             ->whereIn('date', $datesToConsider)
    //             ->where('meal_status', 'on') 
    //             ->sum('quantity');

    //         $cookSalarySum = DB::table('daliy_cook_salary')
    //             ->whereIn('salary_date', $datesToConsider)
    //             ->sum('cook_salary');

    //         $totalCostSum = DB::table('daliy_bajars')
    //             ->whereIn('bajar_date', $datesToConsider)
    //             ->sum('total');

    //         $grandTotal = $totalCostSum + $cookSalarySum;

    //         $mealRate = ($mealQtySum > 0) ? round($grandTotal / $mealQtySum, 2) : 0;

    //         $report = [
    //             'date'        => $today,
    //             'meal_qty'    => $mealQtySum,
    //             'total_cost'  => $totalCostSum,
    //             'cook_salary' => $cookSalarySum,
    //             'grand_total' => $grandTotal,
    //             'meal_rate'   => $mealRate,
    //         ];

    //         return entityResponse(['data' => $report]);

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }



public static function execute()
{
    try {
        $startDate = date('Y-m-01'); 
        $endDate   = date('Y-m-d');  

        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $totalMealQty = 0;
        $totalBazarCost = 0;
        $totalCookSalary = 0;
        $totalGrand = 0;

        foreach ($period as $date) {
            $d = $date->format('Y-m-d');

            $mealQty = DB::table('users_meals')
                ->where('date', $d)
                ->where('meal_status', 'on')
                ->sum('quantity');

            $cookSalary = DB::table('daliy_cook_salary')
                ->where('salary_date', $d)
                ->sum('cook_salary');

            $bazarCost = DB::table('daliy_bajars')
                ->where('bajar_date', $d)
                ->sum('total');

            $grandTotal = $bazarCost + $cookSalary;

            // cumulative summation
            $totalMealQty += $mealQty;
            $totalBazarCost += $bazarCost;
            $totalCookSalary += $cookSalary;
            $totalGrand += $grandTotal;
        }

        $mealRate = ($totalMealQty > 0) ? round($totalGrand / $totalMealQty, 2) : 0;

        $cumulativeReport = [
            'period_start' => $startDate,
            'period_end'   => $endDate,
            'total_meal_qty'   => $totalMealQty,
            'total_bazar_cost' => $totalBazarCost,
            'total_cook_salary'=> $totalCookSalary,
            'grand_total'      => $totalGrand,
            'meal_rate'        => $mealRate,
        ];

        return entityResponse(['data' => $cumulativeReport]);

    } catch (\Exception $e) {
        return messageResponse($e->getMessage(), [], 500, 'server_error');
    }
}






}
<?php

namespace App\Modules\Management\ReportManagement\Actions;
use DB;

class MonthlyDetails
{
    static $model = \App\Modules\Management\ReportManagement\Models\Model::class;

    // public static function execute()
    // {
    //     try {

    //             $start_date = request()->input('start_date');
    //             $end_date = request()->input('end_date');

    //             $meals = DB::table('users_meals')
    //                 ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(quantity) as total_meals")
    //                 ->where('meal_status', 'on') 
    //                 ->groupBy('month')
    //                 ->get()
    //                 ->keyBy('month');

    //             $cooks = DB::table('daliy_cook_salary')
    //                 ->selectRaw("DATE_FORMAT(salary_date, '%Y-%m') as month, SUM(cook_salary) as total_salary")
    //                 ->groupBy('month')
    //                 ->get()
    //                 ->keyBy('month');

    //             $costs = DB::table('daliy_bajars')
    //                 ->selectRaw("DATE_FORMAT(bajar_date, '%Y-%m') as month, SUM(total) as total_cost")
    //                 ->groupBy('month')
    //                 ->get()
    //                 ->keyBy('month');

    //             $report = [];

    //             $months = collect($meals->keys())
    //                 ->merge($costs->keys())
    //                 ->merge($cooks->keys())
    //                 ->unique()
    //                 ->sortDesc(); 

    //             foreach ($months as $month) {
    //                 $mealQty    = $meals[$month]->total_meals ?? 0;
    //                 $totalCost  = $costs[$month]->total_cost ?? 0;
    //                 $cookSalary = $cooks[$month]->total_salary ?? 0;
    //                 $grandTotal = $totalCost + $cookSalary;

    //                 $mealRate = ($mealQty > 0) ? round($grandTotal / $mealQty, 2) : 0;

    //                 $report[] = [
    //                     'month'        => $month, 
    //                     'meal_qty'     => $mealQty,
    //                     'total_cost'   => $totalCost,
    //                     'cook_salary'  => $cookSalary,
    //                     'grand_total'  => $grandTotal,
    //                     'meal_rate'    => $mealRate,
    //                 ];
    //             }

    //             if ($start_date && $end_date) {
    //                 $startMonth = date('Y-m', strtotime($start_date));
    //                 $endMonth   = date('Y-m', strtotime($end_date));

    //                 $report = collect($report)->filter(function ($item) use ($startMonth, $endMonth) {
    //                     return $item['month'] >= $startMonth && $item['month'] <= $endMonth;
    //                 })->values()->all();
    //             }

    //             return entityResponse(['data' => $report]);

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }

    // }


    // public static function execute(){

    //     try {

    //         $start_date = request()->input('start_date');
    //         $end_date = request()->input('end_date');

    //         $month = request()->input('month', date('Y-m'));
            

    //         // 1. cook_salary টেবিলকে date অনুযায়ী group করে sum করা
    //         $cookSalaryGrouped = DB::table('daliy_cook_salary')
    //             ->select(
    //                 'salary_date',
    //                 DB::raw('SUM(cook_salary) as cook_salary_sum')
    //             )
    //             ->groupBy('salary_date');

    //         // 2. daily_summary query
    //         $dailySummary = DB::table('daliy_bajars as b')
    //             ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
    //                 $join->on('c.salary_date', '=', 'b.bajar_date');
    //             })
    //             ->whereRaw("DATE_FORMAT(b.bajar_date, '%Y-%m') = ?", [$month])
    //             ->select(
    //                 'b.bajar_date',
    //                 DB::raw('SUM(b.total) as total_bajar_cost'),
    //                 DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary'),
    //                 DB::raw('(SUM(b.total) + IFNULL(c.cook_salary_sum, 0)) as grand_total')
    //             )
    //             ->groupBy('b.bajar_date', 'c.cook_salary_sum')
    //             ->orderBy('b.bajar_date', 'desc')
    //             ->get();

    //         // 3. মাসিক মোট হিসাব (bajar + cook salary + grand_total)
    //         $monthlyTotal = [
    //             'monthly_total_bajar_cost' => $dailySummary->sum('total_bajar_cost'),
    //             'monthly_total_cook_salary' => $dailySummary->sum('cook_salary'),
    //             'monthly_grand_total' => $dailySummary->sum('grand_total'),
    //         ];


    //         if ($start_date && $end_date) {
    //              if ($end_date > $start_date) {
    //                 $data->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
    //             } elseif ($end_date == $start_date) {
    //                 $data->whereDate('created_at', $start_date);
    //             }
    //         }

    //         // 4. details query
    //         $details = DB::table('daliy_bajars as b')
    //             ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
    //                 $join->on('c.salary_date', '=', 'b.bajar_date');
    //             })
    //             ->whereRaw("DATE_FORMAT(b.bajar_date, '%Y-%m') = ?", [$month])
    //             ->select(
    //                 'b.bajar_date',
    //                 'b.title',
    //                 'b.quantity',
    //                 'b.unit',
    //                 'b.price',
    //                 'b.total as bajar_total',
    //                 DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary')
    //             )
    //             ->orderBy('b.bajar_date', 'desc')
    //             ->get();



    //             return response()->json([
    //                 'daily_summary' => $dailySummary,
    //                 'monthly_total' => $monthlyTotal,
    //                 'details' => $details,
    //             ]);
                
    //         //  return entityResponse($data);

    //         } catch (\Exception $e) {
    //             return messageResponse($e->getMessage(), [], 500, 'server_error');
    //         }
    // }



    public static function execute()
    {
        try {
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            $month = request()->input('month', date('Y-m'));

            // cook_salary টেবিল group
            $cookSalaryGrouped = DB::table('daliy_cook_salary')
                ->select(
                    'salary_date',
                    DB::raw('SUM(cook_salary) as cook_salary_sum')
                )
                ->groupBy('salary_date');

            // daily_summary query
            $dailySummaryQuery = DB::table('daliy_bajars as b')
                ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
                    $join->on('c.salary_date', '=', 'b.bajar_date');
                })
                ->select(
                    'b.bajar_date',
                    DB::raw('SUM(b.total) as total_bajar_cost'),
                    DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary'),
                    DB::raw('(SUM(b.total) + IFNULL(c.cook_salary_sum, 0)) as grand_total')
                )
                ->groupBy('b.bajar_date', 'c.cook_salary_sum')
                ->orderBy('b.bajar_date', 'desc');

            // date filtering
            if ($start_date && $end_date) {
                if ($end_date > $start_date) {
                    $dailySummaryQuery->whereBetween('b.bajar_date', [
                        $start_date . ' 00:00:00',
                        $end_date . ' 23:59:59'
                    ]);
                } elseif ($end_date == $start_date) {
                    $dailySummaryQuery->whereDate('b.bajar_date', $start_date);
                }
            } else {
                $dailySummaryQuery->whereRaw("DATE_FORMAT(b.bajar_date, '%Y-%m') = ?", [$month]);
            }

            $dailySummary = $dailySummaryQuery->get();

            // মাসিক টোটাল
            $monthlyTotal = [
                'monthly_total_bajar_cost' => $dailySummary->sum('total_bajar_cost'),
                'monthly_total_cook_salary' => $dailySummary->sum('cook_salary'),
                'monthly_grand_total' => $dailySummary->sum('grand_total'),
            ];

            // details query
            $detailsQuery = DB::table('daliy_bajars as b')
                ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
                    $join->on('c.salary_date', '=', 'b.bajar_date');
                })
                ->select(
                    'b.bajar_date',
                    'b.title',
                    'b.quantity',
                    'b.unit',
                    'b.price',
                    'b.total as bajar_total',
                    DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary')
                )
                ->orderBy('b.bajar_date', 'desc');

            if ($start_date && $end_date) {
                if ($end_date > $start_date) {
                    $detailsQuery->whereBetween('b.bajar_date', [
                        $start_date . ' 00:00:00',
                        $end_date . ' 23:59:59'
                    ]);
                } elseif ($end_date == $start_date) {
                    $detailsQuery->whereDate('b.bajar_date', $start_date);
                }
            } else {
                $detailsQuery->whereRaw("DATE_FORMAT(b.bajar_date, '%Y-%m') = ?", [$month]);
            }

            $details = $detailsQuery->get();

            return response()->json([
                'daily_summary' => $dailySummary,
                'monthly_total' => $monthlyTotal,
                'details' => $details,
            ]);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }


    // public static function execute()
    // {
    //     try {
    //         $startDate = request()->input('start_date');
    //         $endDate = request()->input('end_date');
    //         $monthInput = request()->input('month', date('Y-m')); // e.g., '2025-08'

    //         // Extract year and month
    //         [$year, $month] = explode('-', $monthInput);

    //         // Prepare cook_salary grouped subquery
    //         $cookSalaryGrouped = DB::table('daliy_cook_salary')
    //             ->select('salary_date', DB::raw('SUM(cook_salary) as cook_salary_sum'))
    //             ->groupBy('salary_date');

    //         // Base daily summary query
    //         $dailySummaryQuery = DB::table('daliy_bajars as b')
    //             ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
    //                 $join->on('c.salary_date', '=', 'b.bajar_date');
    //             })
    //             ->select(
    //                 'b.bajar_date',
    //                 DB::raw('SUM(b.total) as total_bajar_cost'),
    //                 DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary'),
    //                 DB::raw('(SUM(b.total) + IFNULL(c.cook_salary_sum, 0)) as grand_total')
    //             )
    //             ->groupBy('b.bajar_date', 'c.cook_salary_sum')
    //             ->orderBy('b.bajar_date', 'desc');

    //         // Base details query
    //         $detailsQuery = DB::table('daliy_bajars as b')
    //             ->leftJoinSub($cookSalaryGrouped, 'c', function ($join) {
    //                 $join->on('c.salary_date', '=', 'b.bajar_date');
    //             })
    //             ->select(
    //                 'b.bajar_date',
    //                 'b.title',
    //                 'b.quantity',
    //                 'b.unit',
    //                 'b.price',
    //                 'b.total as bajar_total',
    //                 DB::raw('IFNULL(c.cook_salary_sum, 0) as cook_salary')
    //             )
    //             ->orderBy('b.bajar_date', 'desc');

    //         // Apply month + optional date filter
    //         if ($startDate && $endDate) {
    //             if ($endDate > $startDate) {
    //                 $dailySummaryQuery->whereBetween('b.bajar_date', [$startDate, $endDate]);
    //                 $detailsQuery->whereBetween('b.bajar_date', [$startDate, $endDate]);
    //             } elseif ($endDate == $startDate) {
    //                 $dailySummaryQuery->whereDate('b.bajar_date', $startDate);
    //                 $detailsQuery->whereDate('b.bajar_date', $startDate);
    //             }
    //         } else {
    //             $dailySummaryQuery->whereYear('b.bajar_date', $year)
    //                             ->whereMonth('b.bajar_date', $month);
    //             $detailsQuery->whereYear('b.bajar_date', $year)
    //                         ->whereMonth('b.bajar_date', $month);
    //         }

    //         // Fetch results
    //         $dailySummary = $dailySummaryQuery->get();
    //         $details = $detailsQuery->get();

    //         // Monthly totals
    //         $monthlyTotal = [
    //             'monthly_total_bajar_cost' => $dailySummary->sum('total_bajar_cost'),
    //             'monthly_total_cook_salary' => $dailySummary->sum('cook_salary'),
    //             'monthly_grand_total' => $dailySummary->sum('grand_total'),
    //         ];

    //         return response()->json([
    //             'daily_summary' => $dailySummary,
    //             'monthly_total' => $monthlyTotal,
    //             'details' => $details,
    //         ]);

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }



    

}
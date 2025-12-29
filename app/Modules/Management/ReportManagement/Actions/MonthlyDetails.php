<?php

namespace App\Modules\Management\ReportManagement\Actions;
use DB;

class MonthlyDetails
{
    static $model = \App\Modules\Management\ReportManagement\Models\Model::class;


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
<?php

namespace App\Modules\Management\ReportManagement\Actions;
use DB;

class UserData
{
    static $model = \App\Modules\Management\ReportManagement\Models\Model::class;
    static $UsersMeal = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    // public static function execute($userId)
    // {
    //     try {
            
    //         $start_date = request()->input('start_date');
    //         $end_date = request()->input('end_date');
    //         $month = request()->input('month', date('Y-m'));

    //         $user = DB::table('users')
    //             ->where('slug', $userId)
    //             ->first();

    //         if (!$user) {
    //             return response()->json(['message' => 'User not found'], 404);
    //         }


    //         $userMealsDaily = DB::table('users_meals')
    //             ->where('user_id', $user->id)
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as user_meal_qty')
    //             ->groupBy('date')
    //             ->orderBy('date')
    //             ->get()
    //             ->keyBy('date');

    //         $userMealsMonthTotal = DB::table('users_meals')
    //             ->where('user_id', $user->id)
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->sum('quantity');
                
    //         $meals = DB::table('users_meals')
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as total_meals')
    //             ->groupBy('date')
    //             ->get()
    //             ->keyBy('date');

    //         $cooks = DB::table('daliy_cook_salary')
    //             ->whereRaw("DATE_FORMAT(salary_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('salary_date, SUM(cook_salary) as total_salary')
    //             ->groupBy('salary_date')
    //             ->get()
    //             ->keyBy('salary_date');

    //         $costs = DB::table('daliy_bajars')
    //             ->whereRaw("DATE_FORMAT(bajar_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('bajar_date, SUM(total) as total_cost')
    //             ->groupBy('bajar_date')
    //             ->get()
    //             ->keyBy('bajar_date');


    //         // $dates = collect($userMealsDaily->keys())->sort();
    //         $dates = collect($userMealsDaily->keys())->sortDesc();

    //         $report = [];
    //         $totalAmountMonth = 0;

    //         foreach ($dates as $date) {
    //             $mealQty    = $meals[$date]->total_meals ?? 0;
    //             $totalCost  = $costs[$date]->total_cost ?? 0;
    //             $cookSalary = $cooks[$date]->total_salary ?? 0;
    //             $grandTotal = $totalCost + $cookSalary;

    //             $mealRate = ($mealQty > 0) ? round($grandTotal / $mealQty, 2) : 0;

    //             $userMealQty = $userMealsDaily[$date]->user_meal_qty ?? 0;
    //             $userAmount = $userMealQty * $mealRate;

    //             $totalAmountMonth += $userAmount;

    //             $report[] = [
    //                 'date'          => $date,
    //                 'meal_rate'     => $mealRate,
    //                 'user_meal_qty' => $userMealQty,
    //                 'user_amount'   => $userAmount,
    //             ];
    //         }

    //         if ($start_date && $end_date) {
    //             $report = collect($report)->filter(function ($item) use ($start_date, $end_date) {
    //                 return $item['date'] >= $start_date && $item['date'] <= $end_date;
    //             })->values()->all();
    //         }

            
    //         $summary = [
    //             'total_meals_in_month'  => $userMealsMonthTotal,
    //             'total_meal_cost_in_month' => $totalAmountMonth,
    //         ];

    //         // if ($start_date && $end_date) {
    //         //      if ($end_date > $start_date) {
    //         //         $data->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
    //         //     } elseif ($end_date == $start_date) {
    //         //         $data->whereDate('created_at', $start_date);
    //         //     }
    //         // }
            
    //         return [
    //             'user_name'       => $user->name,
    //             'month'           => $month,
    //             'daily_report'    => $report,
    //             'monthly_summary' => $summary,
    //         ];

    //         // return entityResponse($data);

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }

    // public static function execute($userId){
    //     dd('your user id :', $userId);
    // }


    // public static function execute($userId){
    //     try {
    //         $start_date = request()->input('start_date');
    //         $end_date = request()->input('end_date');
    //         $month = request()->input('month', date('Y-m'));

    //         $user = DB::table('users')
    //             ->where('slug', $userId)
    //             ->first();

    //         if (!$user) {
    //             return response()->json(['message' => 'User not found'], 404);
    //         }

    //         // দিনের ভিত্তিতে মোট meal (সকল ইউজার)
    //         $meals = DB::table('users_meals')
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as total_meals')
    //             ->groupBy('date');

    //         // দিনের ভিত্তিতে বাজার খরচ
    //         $costs = DB::table('daliy_bajars')
    //             ->whereRaw("DATE_FORMAT(bajar_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('bajar_date as date, SUM(total) as total_cost')
    //             ->groupBy('bajar_date');

    //         // দিনের ভিত্তিতে রাঁধুনির বেতন
    //         $cooks = DB::table('daliy_cook_salary')
    //             ->whereRaw("DATE_FORMAT(salary_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('salary_date as date, SUM(cook_salary) as total_salary')
    //             ->groupBy('salary_date');

    //         // দিনের ভিত্তিতে ইউজারের meal qty
    //         $userMeals = DB::table('users_meals')
    //             ->where('meal_status', 'on')
    //             ->where('user_id', $user->id)
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as user_meal_qty')
    //             ->groupBy('date');

    //         // Join করে একসাথে ডেটা আনা
    //         $dailyReport = DB::table(DB::raw("({$userMeals->toSql()}) as um"))
    //             ->mergeBindings($userMeals)
    //             ->leftJoin(DB::raw("({$meals->toSql()}) as m"), 'um.date', '=', 'm.date')
    //             ->mergeBindings($meals)
    //             ->leftJoin(DB::raw("({$costs->toSql()}) as c"), 'um.date', '=', 'c.date')
    //             ->mergeBindings($costs)
    //             ->leftJoin(DB::raw("({$cooks->toSql()}) as ck"), 'um.date', '=', 'ck.date')
    //             ->mergeBindings($cooks)
    //             ->selectRaw("
    //                 um.date,
    //                 COALESCE(m.total_meals, 0) as total_meals,
    //                 COALESCE(c.total_cost, 0) as total_cost,
    //                 COALESCE(ck.total_salary, 0) as total_salary,
    //                 COALESCE(um.user_meal_qty, 0) as user_meal_qty,
    //                 ROUND(
    //                     CASE WHEN m.total_meals > 0 
    //                         THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
    //                         ELSE 0 END, 2
    //                 ) as meal_rate,
    //                 ROUND(
    //                     COALESCE(um.user_meal_qty, 0) * 
    //                     CASE WHEN m.total_meals > 0 
    //                         THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
    //                         ELSE 0 END, 2
    //                 ) as user_amount
    //             ")
    //             ->orderBy('um.date', 'desc')
    //             ->get();

    //         // মাসের মোট
    //         $summary = [
    //             'total_meals_in_month' => $userMeals->sum('user_meal_qty'),
    //             'total_meal_cost_in_month' => $dailyReport->sum('user_amount'),
    //         ];

    //         return [
    //             'user_name' => $user->name,
    //             'month' => $month,
    //             'daily_report' => $dailyReport,
    //             'monthly_summary' => $summary
    //         ];

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }


    // public static function execute($userId)
    // {
    //     try {
    //         $start_date = request()->input('start_date');
    //         $end_date = request()->input('end_date');
    //         $month = request()->input('month', date('Y-m'));

    //         $user = DB::table('users')
    //             ->where('slug', $userId)
    //             ->first();

    //         if (!$user) {
    //             return response()->json(['message' => 'User not found'], 404);
    //         }

    //         // মাসের সব date বের করা (যেসব টেবিলে আছে)
    //         $allDates = DB::table('users_meals')
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->select('date')
    //             ->union(
    //                 DB::table('daliy_bajars')
    //                     ->whereRaw("DATE_FORMAT(bajar_date, '%Y-%m') = ?", [$month])
    //                     ->select('bajar_date as date')
    //             )
    //             ->union(
    //                 DB::table('daliy_cook_salary')
    //                     ->whereRaw("DATE_FORMAT(salary_date, '%Y-%m') = ?", [$month])
    //                     ->select('salary_date as date')
    //             );

    //         // dd($allDates);

    //         // দিনের ভিত্তিতে মোট meal (সকল ইউজার)
    //         $meals = DB::table('users_meals')
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as total_meals')
    //             ->groupBy('date');
                
    //         // দিনের ভিত্তিতে বাজার খরচ
    //         $costs = DB::table('daliy_bajars')
    //             ->whereRaw("DATE_FORMAT(bajar_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('bajar_date as date, SUM(total) as total_cost')
    //             ->groupBy('bajar_date');

    //         // দিনের ভিত্তিতে রাঁধুনির বেতন
    //         $cooks = DB::table('daliy_cook_salary')
    //             ->whereRaw("DATE_FORMAT(salary_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('salary_date as date, SUM(cook_salary) as total_salary')
    //             ->groupBy('salary_date');

    //         // দিনের ভিত্তিতে ইউজারের meal qty
    //         $userMeals = DB::table('users_meals')
    //             ->where('user_id', $user->id)
    //             ->where('meal_status', 'on')
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as user_meal_qty')
    //             ->groupBy('date');

    //         // সব date কে base ধরে join করা
    //         $dailyReport = DB::table(DB::raw("({$allDates->toSql()}) as d"))
    //             ->mergeBindings($allDates)
    //             ->leftJoin(DB::raw("({$meals->toSql()}) as m"), 'd.date', '=', 'm.date')
    //             ->mergeBindings($meals)
    //             ->leftJoin(DB::raw("({$costs->toSql()}) as c"), 'd.date', '=', 'c.date')
    //             ->mergeBindings($costs)
    //             ->leftJoin(DB::raw("({$cooks->toSql()}) as ck"), 'd.date', '=', 'ck.date')
    //             ->mergeBindings($cooks)
    //             ->leftJoin(DB::raw("({$userMeals->toSql()}) as um"), 'd.date', '=', 'um.date')
    //             ->mergeBindings($userMeals)
    //             ->selectRaw("
    //                 d.date,
    //                 COALESCE(m.total_meals, 0) as total_meals,
    //                 COALESCE(c.total_cost, 0) as total_cost,
    //                 COALESCE(ck.total_salary, 0) as total_salary,
    //                 COALESCE(um.user_meal_qty, 0) as user_meal_qty,
    //                 ROUND(
    //                     CASE WHEN m.total_meals > 0 
    //                         THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
    //                         ELSE 0 END, 2
    //                 ) as meal_rate,
    //                 ROUND(
    //                     COALESCE(um.user_meal_qty, 0) * 
    //                     CASE WHEN m.total_meals > 0 
    //                         THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
    //                         ELSE 0 END, 2
    //                 ) as user_amount
    //             ")
    //             ->orderBy('d.date', 'desc')
    //             ->get();

    //         // মাসের মোট
    //         $summary = [
    //             'total_meals_in_month' => $userMeals->sum('user_meal_qty'),
    //             'total_meal_cost_in_month' => $dailyReport->sum('user_amount'),
    //         ];

    //         return [
    //             'user_name' => $user->name,
    //             'month' => $month,
    //             'daily_report' => $dailyReport,
    //             'monthly_summary' => $summary
    //         ];

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }


    // public static function execute($userId)
    // {
    //     try {
    //         $start_date = request()->input('start_date');
    //         $end_date = request()->input('end_date');
    //         $month = request()->input('month', date('Y-m'));

    //         $user = DB::table('users')
    //             ->where('slug', $userId)
    //             ->first();

    //         if (!$user) {
    //             return response()->json(['message' => 'User not found'], 404);
    //         }

    //         // দিনের ভিত্তিতে মোট meal (সকল ইউজার)
    //         $meals = DB::table('users_meals')
    //             ->where('meal_status', 'on')
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as total_meals')
    //             ->groupBy('date');

    //         // দিনের ভিত্তিতে বাজার খরচ
    //         $costs = DB::table('daliy_bajars')
    //             ->whereRaw("DATE_FORMAT(bajar_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('bajar_date as date, SUM(total) as total_cost')
    //             ->groupBy('bajar_date');

    //         // দিনের ভিত্তিতে রাঁধুনির বেতন
    //         $cooks = DB::table('daliy_cook_salary')
    //             ->whereRaw("DATE_FORMAT(salary_date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('salary_date as date, SUM(cook_salary) as total_salary')
    //             ->groupBy('salary_date');

    //         // দিনের ভিত্তিতে ইউজারের meal qty
    //         $userMeals = DB::table('users_meals')
    //             ->where('meal_status', 'on')
    //             ->where('user_id', $user->id)
    //             ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
    //             ->selectRaw('date, SUM(quantity) as quantity')
    //             ->groupBy('date');

    //         // Join করে একসাথে ডেটা আনা
    //         $dailyReport = DB::table(DB::raw("({$userMeals->toSql()}) as um"))
    //             ->mergeBindings($userMeals)
    //             ->leftJoin(DB::raw("({$meals->toSql()}) as m"), 'um.date', '=', 'm.date')
    //             ->mergeBindings($meals)
    //             ->leftJoin(DB::raw("({$costs->toSql()}) as c"), 'um.date', '=', 'c.date')
    //             ->mergeBindings($costs)
    //             ->leftJoin(DB::raw("({$cooks->toSql()}) as ck"), 'um.date', '=', 'ck.date')
    //             ->mergeBindings($cooks)
    //             ->selectRaw("
    //                 um.date,
    //                 COALESCE(m.total_meals, 0) as total_meals,
    //                 COALESCE(c.total_cost, 0) as total_cost,
    //                 COALESCE(ck.total_salary, 0) as total_salary,
    //                 COALESCE(um.quantity, 0) as quantity,
    //                 ROUND(
    //                     CASE WHEN m.total_meals > 0 
    //                         THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
    //                         ELSE 0 END, 2
    //                 ) as meal_rate,
    //                 ROUND(
    //                     COALESCE(um.quantity, 0) * 
    //                     CASE WHEN m.total_meals > 0 
    //                         THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
    //                         ELSE 0 END, 2
    //                 ) as user_amount
    //             ")
    //             ->orderBy('um.date', 'desc')
    //             ->get();

                
    //         // মাসের মোট (collection থেকে sum নিচ্ছি)
    //         $summary = [
    //             'total_meals_in_month' => $dailyReport->sum('quantity'),
    //             'total_meal_cost_in_month' => $dailyReport->sum('user_amount'),
    //         ];

    //         return [
    //             'user_name' => $user->name,
    //             'month' => $month,
    //             'daily_report' => $dailyReport,
    //             'monthly_summary' => $summary
    //         ];

    //     } catch (\Exception $e) {
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }



    public static function execute($userId)
    {
        try {
            $start_date = request()->input('start_date');
            $end_date = request()->input('end_date');
            $month = request()->input('month', date('Y-m'));

            $user = DB::table('users')
                ->where('slug', $userId)
                ->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Common date filter callback
            $dateFilter = function ($query, $column) use ($month, $start_date, $end_date) {
                $query->whereRaw("DATE_FORMAT($column, '%Y-%m') = ?", [$month]);
                if ($start_date && $end_date) {
                    if ($end_date > $start_date) {
                        $query->whereBetween($column, [$start_date, $end_date]);
                    } elseif ($end_date == $start_date) {
                        $query->whereDate($column, $start_date);
                    }
                }
            };

            // মোট meal (সকল ইউজার)
            $meals = DB::table('users_meals')
                ->where('meal_status', 'on')
                ->tap(fn($q) => $dateFilter($q, 'date'))
                ->selectRaw('date, SUM(quantity) as total_meals')
                ->groupBy('date');
            // dd('ok', $meals);
            // বাজার খরচ
            $costs = DB::table('daliy_bajars')
                ->tap(fn($q) => $dateFilter($q, 'bajar_date'))
                ->selectRaw('bajar_date as date, SUM(total) as total_cost')
                ->groupBy('bajar_date');

            // রাঁধুনির বেতন
            $cooks = DB::table('daliy_cook_salary')
                ->tap(fn($q) => $dateFilter($q, 'salary_date'))
                ->selectRaw('salary_date as date, SUM(cook_salary) as total_salary')
                ->groupBy('salary_date');

            // ইউজারের meal qty
            $userMeals = DB::table('users_meals')
                ->where('meal_status', 'on')
                ->where('user_id', $user->id)
                ->tap(fn($q) => $dateFilter($q, 'date'))
                ->selectRaw('date, SUM(quantity) as quantity')
                ->groupBy('date');

            // Join করে একসাথে ডেটা আনা
            $dailyReport = DB::table(DB::raw("({$userMeals->toSql()}) as um"))
                ->mergeBindings($userMeals)
                ->leftJoin(DB::raw("({$meals->toSql()}) as m"), 'um.date', '=', 'm.date')
                ->mergeBindings($meals)
                ->leftJoin(DB::raw("({$costs->toSql()}) as c"), 'um.date', '=', 'c.date')
                ->mergeBindings($costs)
                ->leftJoin(DB::raw("({$cooks->toSql()}) as ck"), 'um.date', '=', 'ck.date')
                ->mergeBindings($cooks)
                ->selectRaw("
                    um.date,
                    COALESCE(m.total_meals, 0) as total_meals,
                    COALESCE(c.total_cost, 0) as total_cost,
                    COALESCE(ck.total_salary, 0) as total_salary,
                    COALESCE(um.quantity, 0) as quantity,
                    ROUND(
                        CASE WHEN m.total_meals > 0 
                            THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
                            ELSE 0 END, 2
                    ) as meal_rate,
                    ROUND(
                        COALESCE(um.quantity, 0) * 
                        CASE WHEN m.total_meals > 0 
                            THEN (COALESCE(c.total_cost, 0) + COALESCE(ck.total_salary, 0)) / m.total_meals
                            ELSE 0 END, 2
                    ) as user_amount
                ")
                ->orderBy('um.date', 'desc')
                ->get();

            // মাসের মোট
            $summary = [
                'total_meals_in_month' => $dailyReport->sum('quantity'),
                'total_meal_cost_in_month' => $dailyReport->sum('user_amount'),
            ];

            return [
                'user_name' => $user->name,
                'month' => $month,
                'daily_report' => $dailyReport,
                'monthly_summary' => $summary
            ];

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }





}
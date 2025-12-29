<?php

namespace App\Modules\Management\ReportManagement\Actions;
use DB;

class UserData
{
    static $model = \App\Modules\Management\ReportManagement\Models\Model::class;
    static $UsersMeal = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;


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
<?php
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class DasboardStats
{

    static $model           = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $UserPayment     = \App\Modules\Management\UserManagement\UserPayment\Models\Model::class;
    static $bazarModel      = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;
    static $cookSalaryModel = \App\Modules\Management\ExpenseManagement\CookSalary\Models\Model::class;

    public static function execute()
    {
        try {
            $currentMonth = Carbon::now()->month;
            $currentYear  = Carbon::now()->year;

            // employee all meal quantity
            $employeeAllMealQunatity = self::$model::with('user')
                ->where('user_id', Auth::id())
                ->where('meal_status', 'on')
                ->sum('quantity');

            // employee month meal quantity
            $employeeMonthMealQunatity = self::$model::with('user')
                ->where('user_id', Auth::id())
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->where('meal_status', 'on')
                ->sum('quantity');

            // employee total payable
            $employeeTotalBalance = self::$UserPayment::with('user')
                ->where('user_id', Auth::id())
                ->sum('amount');

            // employee current payable month
            $employeeBalance = self::$UserPayment::with('user')
                ->where('user_id', Auth::id())
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->sum('amount');
                

            $user = Auth::user();

            if (! $user) {
                return messageResponse('User not authenticated', [], 401, 'error');
            }

            $userId = $user->id;

            $currentMonth = date('m');
            $currentYear  = date('Y');

            /**
             * ==============================
             * USER TOTAL MEALS (MONTH)
             * ==============================
             */
            $totalMeals = self::$model::where('user_id', $userId)
                ->where('meal_status', 'on')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->sum('quantity');

            /**
             * ==============================
             * DAILY TOTAL MEALS (ALL USERS)
             * ==============================
             */
            $dailyTotalMeals = self::$model::where('meal_status', 'on')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->selectRaw('date, SUM(quantity) as total_meals')
                ->groupBy('date');

            /**
             * ==============================
             * DAILY BAZAR COST
             * ==============================
             */
            $dailyBazar = self::$bazarModel::whereYear('bajar_date', $currentYear)
                ->whereMonth('bajar_date', $currentMonth)
                ->selectRaw('bajar_date as date, SUM(total) as total_cost')
                ->groupBy('bajar_date');

            /**
             * ==============================
             * DAILY COOK SALARY
             * ==============================
             */

            $dailyCookSalary = DB::table('daliy_cook_salary')
                ->whereYear('salary_date', $currentYear)
                ->whereMonth('salary_date', $currentMonth)
                ->selectRaw('salary_date as date, SUM(cook_salary) as total_salary')
                ->groupBy('salary_date');

            /**
             * ==============================
             * DAILY USER MEALS
             * ==============================
             */
            $dailyUserMeals = self::$model::where('user_id', $userId)
                ->where('meal_status', 'on')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->selectRaw('date, SUM(quantity) as quantity')
                ->groupBy('date');

            /**
             * ==============================
             * MERGED DAILY CALCULATION
             * ==============================
             */
            $dailyReport = \DB::query()
                ->fromSub($dailyUserMeals, 'um')
                ->leftJoinSub($dailyTotalMeals, 'tm', 'um.date', '=', 'tm.date')
                ->leftJoinSub($dailyBazar, 'b', 'um.date', '=', 'b.date')
                ->leftJoinSub($dailyCookSalary, 'cs', 'um.date', '=', 'cs.date')
                ->selectRaw("
                um.date,
                um.quantity,
                COALESCE(tm.total_meals,0) as total_meals,
                COALESCE(b.total_cost,0) as total_cost,
                COALESCE(cs.total_salary,0) as total_salary,
                ROUND(
                    CASE
                        WHEN tm.total_meals > 0
                        THEN (COALESCE(b.total_cost,0) + COALESCE(cs.total_salary,0)) / tm.total_meals
                        ELSE 0
                    END, 2
                ) as meal_rate,
                ROUND(
                    um.quantity *
                    CASE
                        WHEN tm.total_meals > 0
                        THEN (COALESCE(b.total_cost,0) + COALESCE(cs.total_salary,0)) / tm.total_meals
                        ELSE 0
                    END, 2
                ) as user_amount
            ")
                ->orderBy('um.date')
                ->get();

            /**
             * ==============================
             * MONTHLY SUMMARY (FROM DAILY)
             * ==============================
             */
            $totalMealCost = $dailyReport->sum('user_amount');

            $mealRate = $totalMeals > 0 ? round($totalMealCost / $totalMeals, 2) : 0;

            $totalPayments = self::$UserPayment::where('user_id', $userId)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->sum('amount');

            $raw_balance = $totalPayments - $totalMealCost;
            // dd('raw balance', $raw_balance);

            $current_balance = $raw_balance > 0 ? round($raw_balance, 2) : 0.00;
            $due_balance     = $raw_balance < 0 ? round(abs($raw_balance), 2) : 0.00;

            $data = [
                'all_meal_quantity'     => $employeeAllMealQunatity,
                'month_meal_quantity'   => $employeeMonthMealQunatity,
                'total_payable'         => $employeeTotalBalance,
                'current_payable_month' => $employeeBalance,

                // Detailed monthly calculation for the authenticated employee
                'total_meals'           => $totalMeals,
                'meal_rate'             => $mealRate,
                'total_meal_cost'       => $totalMealCost,
                'total_payments'        => $totalPayments,
                'current_balance'       => number_format($current_balance, 2, '.', ''),
                'due_balance'           => number_format($due_balance, 2, '.', ''),
            ];

            // dd('All data', $data);
            return entityResponse($data);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }

}

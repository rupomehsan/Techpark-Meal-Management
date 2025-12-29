<?php
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;
use DB;

class SuperAdminDashboardStats
{
    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $UserPayment = \App\Modules\Management\UserManagement\UserPayment\Models\Model::class;
    static $bazarModel = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;
    static $mealModel = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $cookSalaryModel = \App\Modules\Management\ExpenseManagement\CookSalary\Models\Model::class;



    public static function execute()
    {
        try {
            $currentMonth = now()->month;
            $currentYear  = now()->year;

            // dd('super admin dashboard stats action');
            // $previousMonthAllUserBalance = self::$UserPayment::where('created_at', '<', now()->startOfMonth())
            //                             ->sum('amount');
            //     dd('previous month all user balance', $previousMonthAllUserBalance);


            // Previous all user balance (before current month)
            $previousMonthAllUserBalance = self::$UserPayment:: whereMonth('created_at', now()->subMonth()->month)
                                        ->whereYear('created_at', now()->subMonth()->year)
                                        ->sum('amount');
            // dd('previous month all user balance', $previousMonthAllUserBalance);

            // Current month total payment
            $currentMonthAllUserPayments = self::$UserPayment::whereMonth('created_at', $currentMonth)
                                        ->whereYear('created_at', $currentYear)
                                        ->sum('amount');
            // dd('all user payment', $currentMonthAllUserPayments);

            $currentMonthBazarExpenses = self::$bazarModel::whereMonth('bajar_date', $currentMonth)
                                        ->whereYear('bajar_date', $currentYear)
                                        ->sum('total');
            // dd('bazar expenses', $currentMonthBazarExpenses);

            $currentMonthCookSalaries = DB::table('daliy_cook_salary')->whereMonth('salary_date', $currentMonth)
                                        ->whereYear('salary_date', $currentYear)
                                        ->sum('cook_salary');
            // dd('cook salaries', $currentMonthCookSalaries);

            // balance calculation
            $balance = ($previousMonthAllUserBalance + $currentMonthAllUserPayments) - $currentMonthBazarExpenses;
            // 100 + 1365 - 300 = 1165
            // dd('balance', $balance);

            // current month total meal quantity
            $currentMonthTotalMeal = self::$mealModel::whereMonth('date', $currentMonth)
                                    ->whereYear('date', $currentYear)
                                    ->where('meal_status', 'on')
                                    ->sum('quantity');
            // dd('total meal', $currentMonthTotalMeal);

            //Tomorrow total meal quantity
            $tomorrowTotalMeal = self::$mealModel::whereDate('date', now()->addDay()->toDateString())
                                ->where('meal_status', 'on')
                                ->sum('quantity');
            // dd('tomorrow total meal', $tomorrowTotalMeal);

            // current month per meal rate
            // $currentMonthPerMealRate = $currentMonthTotalMeal > 0 ? round($balance / $currentMonthTotalMeal, 2) : 0;
            // daily bajar + cook salary
            $totalCost = $currentMonthBazarExpenses + $currentMonthCookSalaries;
            // dd('total cost', $totalCost);
            $currentMonthPerMealRate = $currentMonthTotalMeal > 0 ? ($totalCost / $currentMonthTotalMeal) : 0;
            // dd('per meal rate', $currentMonthPerMealRate);

            // Your logic for super admin dashboard stats goes here
            $data = [
                'previousMonthAllUserBalance' => $previousMonthAllUserBalance,
                'currentMonthAllUserPayments' => $currentMonthAllUserPayments,
                'currentMonthBazarExpenses' => $currentMonthBazarExpenses,
                'currentMonthCookSalary' => $currentMonthCookSalaries,
                'balance' => $balance,
                'currentMonthTotalMeal' => $currentMonthTotalMeal,
                'tomorrowTotalMeal' => $tomorrowTotalMeal,
                'currentMonthPerMealRate' => $currentMonthPerMealRate,
            ];

            // dd('data', $data);
            return entityResponse($data);
        } catch (\Exception $e) {
            return messageResponse('Error fetching Super Admin Dashboard Stats: ' . $e->getMessage(), [], 500, 'error');
        }
    }

}
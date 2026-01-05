<?php 

namespace App\Modules\Management\DueListManagement\UserDueList\Actions;
use DB;
use Auth;
use Carbon\Carbon;

class DueList
{
    static $model = \App\Modules\Management\DueListManagement\UserDueList\Models\Model::class;

    static $userModel = \App\Modules\UserManagement\User\Models\Model::class;
    static $paymentModel = \App\Modules\UserManagement\UserPayment\Models\Model::class;
    static $mealModel = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $expenseModel = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

    // public static function execute(){
    //     // dd('here');
    //     try{
    //         $currentMonth = Carbon::now()->month;
    //         $currentYear  = Carbon::now()->year;
            
    //         $totalMeals = self::$mealModel::with('user')->where('meal_status', 'on')
    //                         ->whereMonth('date', $currentMonth)
    //                         ->whereYear('date', $currentYear)
    //                         ->sum('quantity');

    //         $Expense = self::$expenseModel::whereMonth('bajar_date', $currentMonth)
    //                         ->whereYear('bajar_date', $currentYear)
    //                         ->sum('total');

    //         $daliy_cook_salary = DB::table('daliy_cook_salary')
    //                             ->whereYear('salary_date', $currentYear)
    //                             ->whereMonth('salary_date', $currentMonth)
    //                             ->sum('cook_salary');
    //                         // dd('total expense',$daliy_cook_salary);

    //         $totalExpense = $Expense + $daliy_cook_salary;
    //         // dd('total expense',$totalExpense);
    //         $mealRate = $totalMeals > 0 ? ($totalExpense / $totalMeals) : 0;

    //         // dd('meal rate',$mealRate);


    //         $userMeals = self::$mealModel::with('user')
    //             ->select(
    //                 'user_id',
    //                 DB::raw('SUM(quantity) as total_meal')
    //             )
    //             ->where('meal_status', 'on')
    //             ->whereMonth('date', $currentMonth)
    //             ->whereYear('date', $currentYear)
    //             ->groupBy('user_id')
    //             ->get();

    //         // dd('user meals',$userMeals);

    //         $userPayments = DB::table('user_payments')
    //                         ->select(
    //                             'user_id',
    //                             DB::raw('SUM(amount) as total_paid')
    //                         )
    //                         ->whereMonth('created_at', $currentMonth)
    //                         ->whereYear('created_at', $currentYear)
    //                         ->groupBy('user_id')
    //                         ->get()
    //                         ->keyBy('user_id');

    //             // dd('user payment keyed',$userPayments);

    //         $dueList = [];

    //         foreach ($userMeals as $meal) {

    //             $paid = $userPayments[$meal->user_id]->total_paid ?? 0;

    //             $mealCost = $meal->total_meal * $mealRate;
    //             $due      = $mealCost - $paid;

    //             $dueList[] = [
    //                 'user_id'     => $meal->user_id,
    //                 'user_name'   => $meal->user->name ?? null,
    //                 'total_meal'  => $meal->total_meal,
    //                 'meal_cost'   => round($mealCost, 2),
    //                 'paid_amount' => $paid,
    //                 'due_amount'  => round($due, 2),
    //             ];
    //         }


    //         // dd('due list',$dueList);
    //         return messageResponse('All users due list fetched successfully', $dueList, 200, 'success');



    //         // $data->restore();
    //         // return messageResponse('Data successfully destroy...', [], 201, 'success');
    //     }catch(\Exception $e){
    //         return messageResponse($e->getMessage(), [], 500, 'server_error');
    //     }
    // }


    public static function execute()
    {
        try {
            $startMonth = request()->input('start_month'); 
            $endMonth = request()->input('end_month');   

            //  if ($startMonth && $endMonth) {
            //         if ($endMonth > $startMonth) {
            //             $query->whereBetween('created_at', [ $startMonth . ' 00:00:00', $endMonth . ' 23:59:59']);
            //         } elseif ($endMonth == $startMonth) {
            //             $query->whereDate('created_at', $start_date);
            //         }
            //     }

            //  Get all months where meals exist
            // $months = self::$mealModel::select(
            //         DB::raw("DATE_FORMAT(date, '%Y-%m') as month")
            //     )
            //     ->where('meal_status', 'on')
            //     ->groupBy('month')
            //     ->orderBy('month', 'desc')
            //     ->pluck('month');

            $monthsQuery = self::$mealModel::select(
                    DB::raw("DATE_FORMAT(date, '%Y-%m') as month")
                )
                ->where('meal_status', 'on');

            if ($startMonth && $endMonth) {
                $monthsQuery->whereRaw(
                    "DATE_FORMAT(date, '%Y-%m') BETWEEN ? AND ?",
                    [$startMonth, $endMonth]
                );
            }

            $months = $monthsQuery
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->pluck('month');


            $finalDueList = [];

            foreach ($months as $month) {

                [$year, $monthNumber] = explode('-', $month);

                //  Total meals of this month
                $totalMeals = self::$mealModel::where('meal_status', 'on')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthNumber)
                    ->sum('quantity');

                //  Total expense
                $expense = self::$expenseModel::whereYear('bajar_date', $year)
                    ->whereMonth('bajar_date', $monthNumber)
                    ->sum('total');

                $dailyCookSalary = DB::table('daliy_cook_salary')
                    ->whereYear('salary_date', $year)
                    ->whereMonth('salary_date', $monthNumber)
                    ->sum('cook_salary');

                $totalExpense = $expense + $dailyCookSalary;

                $mealRate = $totalMeals > 0 ? ($totalExpense / $totalMeals) : 0;

                //  User meals
                $userMeals = self::$mealModel::with('user')
                    ->select(
                        'user_id',
                        DB::raw('SUM(quantity) as total_meal')
                    )
                    ->where('meal_status', 'on')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $monthNumber)
                    ->groupBy('user_id')
                    ->get();

                //  User payments
                $userPayments = DB::table('user_payments')
                    ->select(
                        'user_id',
                        DB::raw('SUM(amount) as total_paid')
                    )
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $monthNumber)
                    ->groupBy('user_id')
                    ->get()
                    ->keyBy('user_id');

                //  Due calculation
                foreach ($userMeals as $meal) {

                    $paid = $userPayments[$meal->user_id]->total_paid ?? 0;
                    $mealCost = $meal->total_meal * $mealRate;
                    $due = max($mealCost - $paid, 0);             // if paid >= cost, due = 0
                    $advance = max($paid - $mealCost, 0);        // if paid > cost, advance = extra amount

                    $finalDueList[] = [
                        'month'          => $month,
                        'user_id'        => $meal->user_id,
                        'user_name'      => $meal->user->name ?? null,
                        'total_meal'     => $meal->total_meal,
                        'meal_rate'      => round($mealRate, 2),
                        'meal_cost'      => round($mealCost, 2),
                        'paid_amount'    => round($paid, 2),
                        'due_amount'     => round($due, 2),
                        'advance_amount' => round($advance, 2), // show advance if any
                    ];

                }
            }

            return messageResponse('Month-wise due list fetched successfully', $finalDueList, 200,'success');

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }


}
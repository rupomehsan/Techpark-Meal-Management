<?php
    namespace App\Modules\Management\UserManagement\User\Actions;

    class UserCurrentBlance{
        
        static $model = \App\Modules\Management\UserManagement\User\Models\Model::class;
        static $mealModel = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
        static $paymentModel = \App\Modules\Management\UserManagement\UserPayment\Models\Model::class;
        static $bazarModel = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

        public static function execute($slug, $month = null){
            try {
                // dd('id, month', $id, $month);
                $currentMonth = $month ? date('m', strtotime($month)) : date('m');
                $currentYear  = $month ? date('Y', strtotime($month)) : date('Y');
                
                $users = self::$model::where('slug', $slug);
                // dd('users', $user);
                if (!$users) {
                    return messageResponse('User not found', [], 404, 'error');
                }

                $responseData = [];

                foreach ($users as $user) {
                    $id = $user->id;
                    
                    $totalMeals = self::$mealModel::where('user_id', $id)
                        ->where('meal_status', 'on')
                        ->whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->sum('quantity');
                    
                        // dd('totalMeals', $totalMeals);
                    
                    $totalPayments = self::$paymentModel::where('user_id', $id)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->sum('amount');
                    
                    $totalMealQty    = 0;
                    $totalBazarCost  = 0;
                    $totalCookSalary = 0;
                    $totalGrand      = 0;

                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = date("Y-m-d", strtotime("$currentYear-$currentMonth-$day"));

                        $mealQty = self::$mealModel::where('user_id', $id)
                            ->where('date', $date)
                            ->where('meal_status', 'on')
                            ->sum('quantity');

                        $cookSalary = DB::table('daliy_cook_salary')
                            ->where('salary_date', $date)
                            ->sum('cook_salary');

                        $bazarCost = self::$bazarModel::where('bajar_date', $date)->sum('total');

                        $grandTotal = $bazarCost + $cookSalary;

                        $totalMealQty    += $mealQty;
                        $totalBazarCost  += $bazarCost;
                        $totalCookSalary += $cookSalary;
                        $totalGrand      += $grandTotal;
                    }

                    $mealRate = ($totalMealQty > 0) ? round($totalGrand / $totalMealQty, 2) : 0;

                    $mealCost = $totalMeals * $mealRate;

                    $balance = $totalPayments - $mealCost;

                    $responseData[] = [
                        'user_id'        => $user->id,
                        'name'           => $user->name,
                        'total_meals'    => $totalMeals,
                        'meal_rate'      => $mealRate,
                        'total_meal_cost'      => $mealCost,
                        'total_payments' => $totalPayments,
                        'current_balance'        => $balance,
                    ];
                }

                return messageResponse('All users balance fetched successfully', $responseData, 200, 'success');
            } catch (\Exception $e) {
                return messageResponse($e->getMessage(), [], 500, 'server_error');
            }
        }
    }

?>
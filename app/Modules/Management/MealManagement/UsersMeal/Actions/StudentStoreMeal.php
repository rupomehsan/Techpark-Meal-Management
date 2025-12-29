<?php
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;

use Carbon\Carbon;
use DB;

class StudentStoreMeal
{
    static $model            = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $userPaymentmodel = \App\Modules\Management\UserManagement\UserPayment\Models\Model::class;
    static $dailyBajarmodel  = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

    public static function execute($request, $userId)
    {
        try {
            // dd($request);
            $requestData = $request->validated();
            // $requestData['user_id'] = Auth::user()->id;
            // dd('student store meal action', $requestData, $userId);

            if ($userId && isset($requestData['user_id'])) {
                $requestData['user_id'] = $userId;
            }

            $userId = $requestData['user_id'];
            $date   = $requestData['date'];
            $qty    = isset($requestData['quantity']) ? (int) $requestData['quantity'] : 1;
            // dd('quantity', $qty);

            // enforce maximum 1 quantity per creation/update
            if ($qty < 1) {
                $qty = 1;
            }

            if ($qty > 1) {
                $qty = 1;
            }

            // --- Balance check ---
            $today        = Carbon::parse($date);
            $currentMonth = $today->month;
            $currentYear  = $today->year;

            // total payments by user (all time or consider month if you prefer)
            $totalPayments = self::$userPaymentmodel::where('user_id', $userId)
                            ->whereYear('created_at', $currentYear)
                            ->whereMonth('created_at', $currentMonth)
                            ->sum('amount');
            // dd('total payment', $totalPayments);

            $totalBazar = self::$dailyBajarmodel::whereYear('bajar_date', $currentYear)
                            ->whereMonth('bajar_date', $currentMonth)
                            ->sum('total');
            // dd('total bazar', $totalBazar); // 405 

            $totalCookSalary = DB::table('daliy_cook_salary')
                ->whereYear('salary_date', $currentYear)
                ->whereMonth('salary_date', $currentMonth)
                ->sum('cook_salary');
            // dd('total cook salary', $totalCookSalary); // 200

            // $dailyTotalMeals = self::$model::where('meal_status', 'on')
            //     ->whereYear('date', $currentYear)
            //     ->whereMonth('date', $currentMonth)
            //     ->selectRaw('date, SUM(quantity) as total_meals')
            //     ->groupBy('date');
                // dd('daily total meals', $dailyTotalMeals->get());

            $totalMealsAll = self::$model::where('meal_status', 'on')
                // ->where('user_id', $userId)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->sum('quantity');
                // dd('total meals all', $totalMealsAll); // total meal 13

            $mealRate = ($totalMealsAll > 0) ? round(($totalBazar + $totalCookSalary) / $totalMealsAll, 2) : 0; // 405 + 200 = 605 / 13 = 46.54
            // dd('meal rate', $mealRate); // 46.54

            // user's current meals this month 
            $userMealsThisMonth = self::$model::
                where('meal_status', 'on')
                ->where('user_id', $userId)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->sum('quantity'); // meal qty 3 / 13
            // dd('user meals this month', $userMealsThisMonth);

            $userMealCostSoFar = $userMealsThisMonth * $mealRate; 
            // dd('user meal cost so far', $userMealCostSoFar);
            // cost of the requested meal(s) (we limited qty to 1)
            $requestedMealCost = $qty * $mealRate;  // 46.54
            // dd('requested meal cost',  $requestedMealCost); 
            // available balance (payments - cost so far)
            $availableBalance = $totalPayments - $userMealCostSoFar; 
            // dd('available balance', $availableBalance); 

            if ($availableBalance < $requestedMealCost) {
                return messageResponse('Insufficient balance. Please add money to your account to take a meal.', [], 402, 'insufficient_balance');
            }

            $data = self::$model::where('user_id', $userId)
                ->whereDate('date', $date)
                ->first();

            if ($data) {
                // ensure not increasing above 1
                $newQty = $data->quantity + $qty;
                if ($newQty > 1) {
                    $newQty = 1;
                }

                $data->quantity = $newQty;
                $data->save();

                return messageResponse('Meal updated successfully', $data, 200, 'success');
            } else {
                // ensure requestData quantity is max 1
                $requestData['quantity'] = 1;
                // Create new meal
                $data = self::$model::create($requestData);

                return messageResponse('Meal added successfully', $data, 201, 'success');
            }

            // if($data = self::$model::query()->create($requestData)){
            //     return messageResponse('Data added successful..', $data, 200, 'success');
            // }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }


   

}

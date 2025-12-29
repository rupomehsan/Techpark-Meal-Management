<?php 
// kaj kora hoini
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;
use Auth;
use DB;
use Carbon\Carbon;

class StudentMealUpdate 
{
    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;
    static $userPaymentmodel = \App\Modules\Management\UserManagement\UserPayment\Models\Model::class;
    static $dailyBajarmodel  = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

        // public static function execute($request, $slug)
        // {
        //     try {
        //         $requestData = $request->validated();
                
        //         if (!$data = self::$model::query()->where('slug', $slug)->first()) {
        //             return messageResponse('Data not found...',$data, 404, 'error');
        //         }
        //         $data->update($requestData);
        //         return messageResponse('Item updated successfully',$data, 201);
        //     } catch (\Exception $e) {
        //         return messageResponse($e->getMessage(),[], 500, 'server_error');
        //     }
        // }



    public static function execute($request, $slug)
    {
        try {
            $requestData = $request->validated();
            $userId = auth()->id();
            // dd('student meal update action', $requestData, $slug, $userId);
            $data = self::$model::where('slug', $slug)
                ->where('user_id', $userId)
                ->first();

            if (!$data) {
                return messageResponse('Meal not found', [], 404, 'error');
            }

            $date = $requestData['date'] ?? $data->date;
            $qty  = isset($requestData['quantity']) ? (int)$requestData['quantity'] : 1;

            if ($qty < 1) {
                return messageResponse('Quantity must be at least 1', [], 422, 'validation_error');
            }

            if ($qty > 1) {
                return messageResponse('Maximum 1 meal allowed for students', [], 422, 'validation_error');
            }

            //  month/year
            $today        = Carbon::parse($date);
            $currentMonth = $today->month;
            $currentYear  = $today->year;

            //  total payments
            $totalPayments = self::$userPaymentmodel::where('user_id', $userId)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->sum('amount'); // total payments 265
                // dd('total payment', $totalPayments);

            //  total bazar
            $totalBazar = self::$dailyBajarmodel::whereYear('bajar_date', $currentYear)
                ->whereMonth('bajar_date', $currentMonth)
                ->sum('total'); // 300.0
                // dd('total bazar', $totalBazar);

            //  cook salary
            $totalCookSalary = DB::table('daliy_cook_salary')
                ->whereYear('salary_date', $currentYear)
                ->whereMonth('salary_date', $currentMonth)
                ->sum('cook_salary'); // 100.0 
                // dd('total cook salary', $totalCookSalary);

            //  total meals (all users)
            $totalMealsAll = self::$model::where('meal_status', 'on')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->sum('quantity'); // 9
                // dd('total meals all', $totalMealsAll);

            $mealRate = $totalMealsAll > 0 ? round(($totalBazar + $totalCookSalary) / $totalMealsAll, 2) : 0;
            // ( 300 + 100 ) / 9 = 44.44
            // dd('meal rate', $mealRate);

            //  user meals (exclude current meal)
            $userMealsThisMonth = self::$model::where('meal_status', 'on')
                ->where('user_id', $userId)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                // ->where('id', '!=', $data->id)
                ->sum('quantity'); // 
                // dd('user meals this month', $userMealsThisMonth);

            $userMealCostSoFar = $userMealsThisMonth * $mealRate; // 44.44 * 4 = 177.76 
            // dd('user meal cost so far', $userMealCostSoFar);
            $requestedMealCost = $qty * $mealRate; // 44.44 * 1 = 44.44
            // dd('requested meal cost',  $requestedMealCost);
            $availableBalance  = $totalPayments - $userMealCostSoFar; // 265 - 177.76 = 87.24
            // dd('available balance', $availableBalance);

            //  balance insufficient
            if ($availableBalance < $requestedMealCost) {
                return messageResponse(
                    'Insufficient balance. Please add money to your account.',
                    [],
                    402,
                    'insufficient_balance'
                );
            }

            //  update meal
            $data->update([
                'quantity' => 1,
                'date'     => $date
            ]);

            return messageResponse('Meal updated successfully', $data, 200, 'success');

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }

        


        
}
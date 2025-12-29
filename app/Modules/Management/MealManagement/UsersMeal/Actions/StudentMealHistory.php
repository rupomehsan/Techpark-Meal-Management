<?php 
namespace App\Modules\Management\MealManagement\UsersMeal\Actions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentMealHistory{

    static $model = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute()
        {

            try {
                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;

                $data = self::$model::with('user')
                    ->where('user_id', Auth::id())       
                    ->whereYear('date', $currentYear)  
                    ->whereMonth('date', $currentMonth) 
                    ->where('meal_status', 'on')
                    ->get();
                    
                return entityResponse($data);
            } catch (\Exception $e) {
                return messageResponse($e->getMessage(), [], 500, 'server_error');
            }
    }

}
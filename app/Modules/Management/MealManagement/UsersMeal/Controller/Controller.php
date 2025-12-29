<?php
namespace App\Modules\Management\MealManagement\UsersMeal\Controller;

use App\Http\Controllers\Controller as ControllersController;
// for employee meal management
use App\Modules\Management\MealManagement\UsersMeal\Actions\AuthEmployeeGetAllData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\EmployeePaymentHistoryData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\EmployeeMealHistory;
use App\Modules\Management\MealManagement\UsersMeal\Actions\EmployeeStoreMeal;
use App\Modules\Management\MealManagement\UsersMeal\Actions\EmployeeUpdateMeal;

// for student meal management
use App\Modules\Management\MealManagement\UsersMeal\Actions\AuthStudentGetAllData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\StudentStoreMeal;
use App\Modules\Management\MealManagement\UsersMeal\Actions\StudentMealUpdate;
use App\Modules\Management\MealManagement\UsersMeal\Actions\StudentMealHistory;

use App\Modules\Management\MealManagement\UsersMeal\Actions\DasboardStats;
use App\Modules\Management\MealManagement\UsersMeal\Actions\SuperAdminDashboardStats;

use App\Modules\Management\MealManagement\UsersMeal\Actions\CheckOffMeal;
use App\Modules\Management\MealManagement\UsersMeal\Actions\Destroy;
use App\Modules\Management\MealManagement\UsersMeal\Actions\GetAllData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\GetSingleData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\ImportData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\RestoreData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\RoleByUserNameData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\SoftDelete;
use App\Modules\Management\MealManagement\UsersMeal\Actions\StoreData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\ToDayMealData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\UpdateData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\UpdateStatus;
use App\Modules\Management\MealManagement\UsersMeal\Actions\UserMealHistoryData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\UsersMealHistoryData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\UserStoreData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\BulkActions;
use App\Modules\Management\MealManagement\UsersMeal\Validations\BulkActionsValidation;
use App\Modules\Management\MealManagement\UsersMeal\Validations\DataStoreValidation;
use Illuminate\Http\Request;

class Controller extends ControllersController
{

    public function index()
    {
        $data = GetAllData::execute();
        return $data;
    }
   
    public function store(DataStoreValidation $request)
    {
        $data = StoreData::execute($request);
        return $data;
    }

    public function userStore(DataStoreValidation $request)
    {
        $userId = $request->user()->id;
        $data   = UserStoreData::execute($request, $userId);
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }

    public function updateStatus()
    {
        $data = UpdateStatus::execute();
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }

    public function destroy($slug)
    {
        $data = Destroy::execute($slug);
        return $data;
    }

    public function restore()
    {
        $data = RestoreData::execute();
        return $data;
    }

    public function import()
    {
        $data = ImportData::execute();
        return $data;
    }
    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }

    public function roleByuserName($id)
    {
        $data = RoleByUserNameData::execute($id);
        return $data;
    }

    public function toDayMeal($date)
    {
        $data = ToDayMealData::execute($date);
        return $data;
    }

    public function userMealHistory(Request $request, $uid)
    {
        $date = $request->query('date');
        return UserMealHistoryData::execute($uid, $date);
    }

    public function userMealsHistory(Request $request)
    {
        // $userId = $request->user()->id;
        $userId = Auth::user()->id;
        $date   = $request->query('date');
        return UsersMealHistoryData::execute($userId, $date);
    }

    public function checkOffMeal($date)
    {
        $data = CheckOffMeal::execute($date);
        return $data;
    }

    // for employee meal management

    public function authGetAllEmployee()
    {
        $data = AuthEmployeeGetAllData::execute();
        return $data;
    }

    public function employeeStoreMeal(DataStoreValidation $request)
    {
        $userId = $request->user()->id;
        $data   = EmployeeStoreMeal::execute($request, $userId);
        return $data;
    }

    public function employeeUpdateMeal(DataStoreValidation $request, $slus)
    {
        $data = EmployeeUpdateMeal::execute($request, $slus);
        return $data;
    }

    public function employeeMonthlyMeal($date)
    {
        $data = EmployeeMealHistory::execute($date);
        return $data;
    }

    public function DashboardStats()
    {
        $data = DasboardStats::execute();
        return $data;
    }

    public function SuperAdminDashboardStats()
    {
        $data = SuperAdminDashboardStats::execute();
        return $data;
    }

    
    
    // for student meal management
    public function authGetAllStudent()
    {
        $data = AuthStudentGetAllData::execute();
        return $data;
    }

    public function StudentStoreMeal(DataStoreValidation $request)
    {
        $userId = $request->user()->id;
        $data   = StudentStoreMeal::execute($request, $userId);
        // dd('student store meal controller', $data);
        return $data;
    }

    public function studentUpdateMeal(DataStoreValidation $request, $slus)
    {
        $data = StudentMealUpdate::execute($request, $slus);
        return $data;
    }
    
    public function studentMonthlyMeal($date)
    {
        $data = StudentMealHistory::execute($date);
        return $data;
    }


    // public function studentPaymentHistory()
    // {
    //     $data = StudentPaymentHistoryData::execute();
    //     return $data;
    // }



}

<?php
namespace App\Modules\Management\MealManagement\UsersMeal\Controller;

use App\Http\Controllers\Controller as ControllersController;
use App\Modules\Management\MealManagement\UsersMeal\Actions\AuthGetAllData;
use App\Modules\Management\MealManagement\UsersMeal\Actions\BulkActions;
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

    public function authGetAll()
    {
        $data = AuthGetAllData::execute();
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
        // dd('ok');

        // $userId = $request->user()->id;  
        $userId = Auth::user()->id;  
        $date = $request->query('date');
        return UsersMealHistoryData::execute($userId, $date);
    }



    public function checkOffMeal($date)
    {
        $data = CheckOffMeal::execute($date);
        return $data;
    }

}

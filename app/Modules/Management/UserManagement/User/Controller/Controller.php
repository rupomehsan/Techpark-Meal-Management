<?php
namespace App\Modules\Management\UserManagement\User\Controller;

use App\Http\Controllers\Controller as ControllersController;

use App\Modules\Management\UserManagement\User\Actions\BulkActions;
use App\Modules\Management\UserManagement\User\Actions\DepartmentByBatch;
use App\Modules\Management\UserManagement\User\Actions\DestroyData;
use App\Modules\Management\UserManagement\User\Actions\GetAllData;
use App\Modules\Management\UserManagement\User\Actions\GetSingleData;
use App\Modules\Management\UserManagement\User\Actions\ImportData;
use App\Modules\Management\UserManagement\User\Actions\MealHistoryData;
use App\Modules\Management\UserManagement\User\Actions\PaymentHistoryData;
use App\Modules\Management\UserManagement\User\Actions\RestoreData;
use App\Modules\Management\UserManagement\User\Actions\SoftDelete;
use App\Modules\Management\UserManagement\User\Actions\StoreData;
use App\Modules\Management\UserManagement\User\Actions\UpdateData;
use App\Modules\Management\UserManagement\User\Actions\UserBlance;
use App\Modules\Management\UserManagement\User\Actions\UserCurrentBlance;

use App\Modules\Management\UserManagement\User\Actions\UserChangePassword;
use App\Modules\Management\UserManagement\User\Actions\UserProfileUpdate;

use App\Modules\Management\UserManagement\User\Validations\BulkActionsValidation;
use App\Modules\Management\UserManagement\User\Validations\DataStoreValidation;
use App\Modules\Management\UserManagement\User\Validations\UserChangePasswordValidation;
use App\Modules\Management\UserManagement\User\Validations\UserProfileUpdateValidation;

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

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function paymentHistory($id)
    {
        $data = PaymentHistoryData::execute($id);
        return $data;
    }
    public function mealHistory($id)
    {
        $data = MealHistoryData::execute($id);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }

    public function UserProfileUpdate(UserProfileUpdateValidation $request, )
    {
        $data = UserProfileUpdate::execute($request);
        return $data;
    }
    public function UserChangePassword(UserChangePasswordValidation $request, )
    {
        $data = UserChangePassword::execute($request);
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }
    public function destroy($slug)
    {
        $data = DestroyData::execute($slug);
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

    public function departmentBYBatch($id)
    {
        $data = DepartmentByBatch::execute($id);
        return $data;
    }

    public function userCurrentMonthBalance($month = null)
    {
        $data = UserBlance::execute($month);
        return $data;
    }

    public function userCurrentBalance($slug, $month = null)
    {
        $data = UserCurrentBlance::execute($slug, $month);
        return $data;
    }




}

<?php 
namespace App\Modules\Management\ExpenseManagement\CookSalary\Controller;

use App\Http\Controllers\Controller as ControllersController;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\StoreData;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\GetAllData;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\SoftDelete;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\UpdateData;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\BulkActions;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\DestroyData;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\CookSallaryHistoryData;

use App\Modules\Management\ExpenseManagement\CookSalary\Actions\RestoreData;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\UpdateStatus;
use App\Modules\Management\ExpenseManagement\CookSalary\Actions\GetSingleData;

use App\Modules\Management\ExpenseManagement\CookSalary\Validations\DataStoreValidation;
use App\Modules\Management\ExpenseManagement\CookSalary\Validations\BulkActionsValidation;

class Controller extends ControllersController{
    public function index(){
        $data = GetAllData::execute();
        return $data;
    }

    public function show($slug){
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function store(DataStoreValidation $resquest){
        $data = StoreData::execute($resquest);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug){
        $data = UpdateData::execute($request, $slug);
        return $data;
    }

    public function updateStatus()
    {
        $data = UpdateStatus::execute();
        return $data;
    }

    public function softDelete(){
        $data = SoftDelete::execute();
        return $data;
    }

    public function restore(){
        $data = RestoreData::execute();
        return $data;
    }

    public function destroy($slug){
        $data = DestroyData::execute($slug);
        return $data;
    }
    
    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }

    public function cookSallaryHistory($month){
        $data = CookSallaryHistoryData::execute($month);
        return $data;
    }
   

}
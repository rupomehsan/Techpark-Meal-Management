<?php 

namespace App\Modules\Management\ExpenseManagement\DailyBajar\Controller;

use App\Http\Controllers\Controller as ControllersController;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\StoreData;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\GetAllData;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\SoftDelete;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\UpdateData;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\BulkActions;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\DestroyData;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\RestoreData;

use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\UpdateStatus;
use App\Modules\Management\ExpenseManagement\DailyBajar\Actions\GetSingleData;

use App\Modules\Management\ExpenseManagement\DailyBajar\Validations\DataStoreValidation;
use App\Modules\Management\ExpenseManagement\DailyBajar\Validations\BulkActionsValidation;

class Controller extends ControllersController{
    public function index(){
        $data = GetAllData::execute();
        return $data;
    }

    public function show($slug){
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function store(DataStoreValidation $request){
        $data = StoreData::execute($request);
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
}
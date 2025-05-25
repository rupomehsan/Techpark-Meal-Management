<?php 
namespace App\Modules\Management\DueListManagement\UserDueList\Controller;

use App\Http\Controllers\Controller as ControllersController;
use App\Modules\Management\DueListManagement\UserDueList\Actions\StoreData;
use App\Modules\Management\DueListManagement\UserDueList\Actions\GetAllData;
use App\Modules\Management\DueListManagement\UserDueList\Actions\SoftDelete;
use App\Modules\Management\DueListManagement\UserDueList\Actions\UpdateData;
use App\Modules\Management\DueListManagement\UserDueList\Actions\BulkActions;
use App\Modules\Management\DueListManagement\UserDueList\Actions\DestroyData;



use App\Modules\Management\DueListManagement\UserDueList\Actions\RestoreData;
use App\Modules\Management\DueListManagement\UserDueList\Actions\UpdateStatus;
use App\Modules\Management\DueListManagement\UserDueList\Actions\GetSingleData;

use App\Modules\Management\DueListManagement\UserDueList\Validations\DataStoreValidation;
use App\Modules\Management\DueListManagement\UserDueList\Validations\BulkActionsValidation;

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

}
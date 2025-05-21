<?php

namespace App\Modules\Management\UserManagement\UserPayment\Controller;


use App\Modules\Management\UserManagement\UserPayment\Actions\StoreData;
use App\Modules\Management\UserManagement\UserPayment\Actions\GetAllData;
use App\Modules\Management\UserManagement\UserPayment\Actions\ImportData;
use App\Modules\Management\UserManagement\UserPayment\Actions\SoftDelete;
use App\Modules\Management\UserManagement\UserPayment\Actions\UpdateData;
use App\Modules\Management\UserManagement\UserPayment\Actions\BulkActions;
use App\Modules\Management\UserManagement\UserPayment\Actions\DestoryData;
use App\Modules\Management\UserManagement\UserPayment\Actions\RestoreData;
use App\Modules\Management\UserManagement\UserPayment\Actions\UpdateStatus;
use App\Modules\Management\UserManagement\UserPayment\Actions\GetSingleData;

use App\Modules\Management\UserManagement\UserPayment\Validations\DataStoreValidation;
use App\Modules\Management\UserManagement\UserPayment\Validations\BulkActionsValidation;

use App\Http\Controllers\Controller as ControllersController;
class Controller extends ControllersController{

    public function index(){
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
        $data = DestoryData::execute($slug);
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



}
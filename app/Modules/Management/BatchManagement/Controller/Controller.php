<?php 
namespace App\Modules\Management\BatchManagement\Controller;

use \App\Modules\Management\BatchManagement\Actions\GetAllData;
use \App\Modules\Management\BatchManagement\Actions\GetSingleData;
use \App\Modules\Management\BatchManagement\Actions\StoreData;
use \App\Modules\Management\BatchManagement\Actions\UpdateData;
use App\Modules\Management\BatchManagement\Actions\SoftDelete;
use App\Modules\Management\BatchManagement\Actions\RestoreData;
use App\Modules\Management\BatchManagement\Actions\DestroyData;

use App\Http\Controllers\Controller as ControllersController;
use App\Modules\Management\BatchManagement\Validations\DataStoreValidation;

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
}
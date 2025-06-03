<?php

namespace App\Modules\Management\UserManagement\User\Actions;

use Illuminate\Support\Facades\Hash;

class StoreData
{
    static $model = \App\Modules\Management\UserManagement\User\Models\Model::class;

    public static function execute($request)
    {
        // dd($request->all());
        try {
            
            $requestData = $request->validated();



            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $requestData['image'] = uploader($image, 'uploads/users');
            }

            $requestData['password'] = Hash::make($requestData['password']);

        
            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        


        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
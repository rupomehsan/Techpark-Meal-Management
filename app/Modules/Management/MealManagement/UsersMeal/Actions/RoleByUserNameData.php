<?php

namespace App\Modules\Management\MealManagement\UsersMeal\Actions;

class RoleByUserNameData
{
    static $model = \App\Modules\Management\UserManagement\User\Models\Model::class;


    public static function execute($id)
    {
        
        try {
            $role = self::$model::where('role_id', $id)->get();
         
            if ($role->isEmpty()) {
                return response()->json([
                    'message' => 'No role found',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'message' => 'role fetched successfully',
                'data' => $role
            ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error fetching role',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

         
    }

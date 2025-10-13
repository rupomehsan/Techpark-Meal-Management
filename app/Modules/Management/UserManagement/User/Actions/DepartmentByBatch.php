<?php

namespace App\Modules\Management\UserManagement\User\Actions;


class DepartmentByBatch
{
    static $model = \App\Modules\Management\BatchManagement\Models\Model::class;

    public static function execute($department)
    {

        try {
            $batches = self::$model::where('department_id', $department)->get();

            if ($batches->isEmpty()) {
                return response()->json([
                    'message' => 'No batches found',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'message' => 'Batches fetched successfully',
                'data' => $batches
            ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error fetching batches',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    
}
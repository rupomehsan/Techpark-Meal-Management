<?php

namespace App\Modules\Management\UserManagement\User\Actions;

class MealHistoryData
{
    static $model = \App\Modules\Management\UserManagement\User\Models\Model::class;
    public static $models = \App\Modules\Management\MealManagement\UsersMeal\Models\Model::class;

    public static function execute($id)
    {
         try {
                $start_date = request()->input('start_date');
                $end_date = request()->input('end_date');

                // $date = date('Y-m-d');
                $query = self::$models::query()
                    ->where('user_id', $id)
                    ->where('meal_status', 'on')
                    ->orderBy('date', 'desc');

                // dd('here', $query);

                if ($start_date && $end_date) {
                    if ($end_date > $start_date) {
                        $query->whereBetween('created_at', [ $start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                    } elseif ($end_date == $start_date) {
                        $query->whereDate('created_at', $start_date);
                    }
                }

                $data = $query->orderByRaw('DATE(created_at) DESC')->get();

                if ($data->isEmpty()) {
                    return messageResponse('Data not found...', $data, 404, 'error');
                }

                return entityResponse($data);

            } catch (\Exception $e) {
                return messageResponse($e->getMessage(), [], 500, 'server_error');
            }
    }
}



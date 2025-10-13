<?php

namespace App\Modules\Management\ReportManagement\Actions;
use DB;

class UsersAllData
{
    static $model = \App\Modules\Management\ReportManagement\Models\Model::class;

    public static function execute()
    {

        try {
                $start_month = request()->input('start_month'); 
                $end_month = request()->input('end_month');    

                $query = DB::table('users_meals')
                    ->where('meal_status', 'on')
                    ->join('users', 'users_meals.user_id', '=', 'users.id')
                    ->select(
                        'users.slug as user_slug',
                        'users.name as user_name',
                        DB::raw('DATE_FORMAT(users_meals.date, "%Y-%m") as month'),
                        DB::raw('SUM(users_meals.quantity) as total_quantity')
                    );

                if ($start_month && $end_month) {
                    $start_date = $start_month . '-01';
                    $end_date = date('Y-m-t', strtotime($end_month));
                    $query->whereBetween('users_meals.date', [$start_date, $end_date]);
                }

                if ($start_month && $end_month) {
                    $start_date = $start_month . '-01';
                    $end_date = date('Y-m-t', strtotime($end_month));
                    $query->whereBetween('users_meals.date', [$start_date, $end_date]);

                } elseif ($start_month) {
                    $start_date = $start_month . '-01';
                    $query->where('users_meals.date', '>=', $start_date);

                } elseif ($end_month) {
                    $end_date = date('Y-m-t', strtotime($end_month));
                    $query->where('users_meals.date', '<=', $end_date);
                }

                $data = $query
                    ->groupBy('month', 'users.slug', 'users.name')
                    ->orderByDesc('month')
                    ->get();

                if ($data->isEmpty()) {
                    return messageResponse('Data not found...', $data, 404, 'error');
                }

                return entityResponse($data);

            } catch (\Exception $e) {
                return messageResponse($e->getMessage(), [], 500, 'server_error');
            }

    
        // try {
        //         $start_month = request()->input('start_month');
        //         $end_month = request()->input('end_month');
        //         $month = request()->input('month'); 

        //         $query = DB::table('users_meals')
        //             ->join('users', 'users_meals.user_id', '=', 'users.id')
        //             ->select(
        //                 'users.slug as user_slug',
        //                 'users.name as user_name',
        //                 DB::raw('DATE_FORMAT(users_meals.date, "%Y-%m") as month'),
        //                 DB::raw('SUM(users_meals.quantity) as total_quantity')
        //             );

        //         if ($month) {
        //             $start_date = $month . '-01';
        //             $end_date = date('Y-m-t', strtotime($month));
        //             $query->whereBetween('users_meals.date', [$start_date, $end_date]);

        //         } elseif ($start_month && $end_month) {
        //             $start_date = $start_month . '-01';
        //             $end_date = date('Y-m-t', strtotime($end_month));
        //             $query->whereBetween('users_meals.date', [$start_date, $end_date]);

        //         } elseif ($start_month) {
        //             $start_date = $start_month . '-01';
        //             $query->where('users_meals.date', '>=', $start_date);

        //         } elseif ($end_month) {
        //             $end_date = date('Y-m-t', strtotime($end_month));
        //             $query->where('users_meals.date', '<=', $end_date);
        //         }

        //         $data = $query
        //             ->groupBy('month', 'users.slug', 'users.name')
        //             ->orderByDesc('month')
        //             ->get();

        //         if ($data->isEmpty()) {
        //             return messageResponse('Data not found...', $data, 404, 'error');
        //         }

        //         return entityResponse($data);

        //     } catch (\Exception $e) {
        //         return messageResponse($e->getMessage(), [], 500, 'server_error');
        //     }

        

    }
}
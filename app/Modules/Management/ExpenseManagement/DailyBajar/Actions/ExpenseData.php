<?php

namespace App\Modules\Management\ExpenseManagement\DailyBajar\Actions;

class ExpenseData
    {
        static $model = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;
        public static function execute()
        {
            
            try {
                
                $pageLimit = request()->input('limit') ?? 10;
                $orderByColumn = request()->input('sort_by_col') ?? 'id';
                $orderByType = request()->input('sort_type') ?? 'desc';
                $status = request()->input('status') ?? 'active';
                $fields = request()->input('fields') ?? '*';
                $start_date = request()->input('start_date');
                $end_date = request()->input('end_date');
                $with = [];
                $condition = [];

                $data = self::$model::query()
                    ->selectRaw('bajar_date, SUM(total) as total_sum')
                    ->groupBy('bajar_date')
                    ->orderBy('bajar_date', 'desc');
                
                if (request()->has('search') && request()->input('search')) {
                        $searchKey = request()->input('search');
                        $data = $data->where(function ($q) use ($searchKey) {
                        $q->where('total', 'like', '%' . $searchKey . '%');             
                            // ->OrWhere('bajar_date', 'like', '%' . $searchKey . '%');              
                    });
                }
                // dd('ok');

                if ($start_date && $end_date) {
                    if ($end_date > $start_date) {
                        $data->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                    } elseif ($end_date == $start_date) {
                        $data->whereDate('created_at', $start_date);
                    }
                }

                
                if ($status == 'trased') {
                    $data = $data->trased();
                }
                // dd('OK', $data);
                
                if (request()->has('get_all') && (int)request()->input('get_all') === 1) {
                    $data = $data
                        ->with($with)
                        // ->select($fields)
                        ->where($condition)
                        ->where('status', $status)
                        ->limit($pageLimit)
                        // ->orderBy($orderByColumn, $orderByType)
                        ->get();
                        return entityResponse($data);
                    } else if ($status == 'trased') {
                        $data = $data
                        ->with($with)
                        // ->select($fields)
                        ->where($condition)
                        // ->orderBy($orderByColumn, $orderByType)
                        ->paginate($pageLimit);
                    } else {
                        // dd("inside");
                        $data = $data
                        ->with($with)
                        // ->select($fields)
                        ->where($condition)
                        ->where('status', $status)
                        // ->orderBy($orderByColumn, $orderByType)
                        ->paginate($pageLimit);
                        // dd('ok', $data);
                    }

                return entityResponse([
                    ...$data->toArray(),
                    "active_data_count" => self::$model::active()->count(),
                    "inactive_data_count" => self::$model::inactive()->count(),
                    "trased_data_count" => self::$model::trased()->count(),
                ]);

            } catch (\Exception $e) {
                return messageResponse($e->getMessage(), [], 500, 'server_error');
            }

          
          
            
            // try {
            //     $pageLimit = request()->input('limit') ?? 100;
            //     $orderByColumn = request()->input('sort_by_col') ?? 'id';
            //     $orderByType = request()->input('sort_type') ?? 'desc';
            //     $status = request()->input('status') ?? 'active';
            //     $fields = request()->input('fields') ?? '*';
            //     $start_date = request()->input('start_date');
            //     $end_date = request()->input('end_date');
            //     // $searchKey = request()->input('search');
            //     $with = [];
            //     $condition = [];

            //     // $query = self::$model::query();
            //     $query = self::$model::query()
            //         ->selectRaw('bajar_date, SUM(total) as total_sum')
            //         ->groupBy('bajar_date')
            //         ->orderBy('bajar_date', 'desc');
            //     // dd($query);

            //     if ($start_date && $end_date) {
            //         if ($end_date > $start_date) {
            //             $query->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            //         } elseif ($end_date == $start_date) {
            //             $query->whereDate('created_at', $start_date);
            //         }
            //     }

            
            //     if ($status == 'trased') {
            //         $query = $query->trased();
            //     }

            //     if (request()->has('search') && request()->input('search')) {
            //         $searchKey = request()->input('search');
            //         $query = $query->where(function ($q) use ($searchKey) {
            //             $q->where('bajar_date', 'like', '%' . $searchKey . '%')
            //               ->orWhere('total', 'like', '%' . $searchKey . '%');              
            //         });
            //     }


            //     // $query = $query->selectRaw('bajar_date, SUM(total) as total_sum')
            //     //     ->groupBy('bajar_date')
            //     //     ->orderBy('bajar_date', 'desc');
                
            //     if (request()->has('get_all') && (int)request()->input('get_all') === 1) {
            //         $data = $query->get();
            //     } else {
            //         $data = $query->paginate($pageLimit);
            //     }

            //     return entityResponse([
            //         ...$data->toArray(),
            //         "active_data_count" => self::$model::active()->count(),
            //         "inactive_data_count" => self::$model::inactive()->count(),
            //         "trased_data_count" => self::$model::trased()->count(),
            //     ]);

            // } catch (\Exception $e) {
            //     return messageResponse($e->getMessage(), [], 500, 'server_error');
            // }


        }

            
    }
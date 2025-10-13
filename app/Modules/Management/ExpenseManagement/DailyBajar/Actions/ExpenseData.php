<?php

namespace App\Modules\Management\ExpenseManagement\DailyBajar\Actions;
use DB;


class ExpenseData
    {
        static $model = \App\Modules\Management\ExpenseManagement\DailyBajar\Models\Model::class;

        public static function execute()
        {
            
            try {
                
                $start_date = request()->input('start_date');
                $end_date = request()->input('end_date');
                // $with = [];

                // $data = self::$model::query()
                //     ->selectRaw('bajar_date, SUM(total) as total_sum')
                //     ->groupBy('bajar_date')
                //     ->orderBy('bajar_date', 'desc')
                //     ->get();

                // $data = DB::table('daliy_cook_salary')
                //     ->selectRaw('salary_date, SUM(cook_salary) as total_sum')
                //     ->groupBy('salary_date')
                //     ->orderByDesc('salary_date')
                //     ->get();


                // Fetch bajar data
                    $bajarData = self::$model::query()
                        ->selectRaw('bajar_date, SUM(total) as total_sum')
                        ->groupBy('bajar_date')
                        ->orderByDesc('bajar_date')
                        ->get()
                        ->keyBy('bajar_date');

                    // Fetch cook salary data
                    $cookSalaryData = DB::table('daliy_cook_salary')
                        ->selectRaw('salary_date, SUM(cook_salary) as cook_salary')
                        ->groupBy('salary_date')
                        ->orderByDesc('salary_date')
                        ->get()
                        ->keyBy('salary_date');

                    // Combine them
                    $data = [];

                    foreach ($bajarData as $date => $bajar) {
                        $cookSalary = $cookSalaryData[$date]->cook_salary ?? 0;

                        $data[] = [
                            'bajar_date'   => $date,
                            'total_sum'    => $bajar->total_sum,
                            'cook_salary'  => $cookSalary,
                            'grand_total'  => $bajar->total_sum + $cookSalary,
                        ];
                    }

                    // Optional: If you want to show rows that are only in cookSalaryData but not in bajarData
                    foreach ($cookSalaryData as $date => $cook) {
                        if (!isset($bajarData[$date])) {
                            $data[] = [
                                'bajar_date'   => $date,
                                'total_sum'    => 0,
                                'cook_salary'  => $cook->cook_salary,
                                'grand_total'  => $cook->cook_salary,
                            ];
                        }
                    }
                    
                // dd('OK', $data);

                

                if ($start_date && $end_date) {
                    if ($end_date > $start_date) {
                        $data->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                    } elseif ($end_date == $start_date) {
                        $data->whereDate('created_at', $start_date);
                    }
                }

                
            //    if ($data->isEmpty()) {
            //         return messageResponse('Data not found...', $data, 404, 'error');
            //     }

                return entityResponse($data);

                // dd('OK', $data);


                
                // if (request()->has('get_all') && (int)request()->input('get_all') === 1) {
                //     $data = $data
                //         ->where('status', $status)
                //         ->limit($pageLimit)
                //         ->get();
                //         return entityResponse($data);
                //     } else if ($status == 'trased') {
                //         $data = $data
                //         ->paginate($pageLimit);
                //     } else {
                //         $data = $data
                //         ->where('status', $status)
                //         ->paginate($pageLimit);
                //     }

                // return entityResponse([
                //     ...$data->toArray(),
                //     "active_data_count" => self::$model::active()->count(),
                //     "inactive_data_count" => self::$model::inactive()->count(),
                //     "trased_data_count" => self::$model::trased()->count(),
                // ]);

            } catch (\Exception $e) {
                return messageResponse($e->getMessage(), [], 500, 'server_error');
            }


        }
            
    }

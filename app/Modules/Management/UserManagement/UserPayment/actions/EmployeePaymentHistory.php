<?php

namespace App\Modules\Management\UserManagement\UserPayment\Actions;
use Auth;

class EmployeePaymentHistory
{
    public static $paymentModel = \App\Modules\Management\UserManagement\UserPayment\Models\Model::class;

    public static function execute()
    {

        try {
            $start_date = request()->input('start_date');
            $end_date   = request()->input('end_date');

            $userId = Auth::id();
            if (! $userId) {
                return messageResponse('Unauthorized', [], 401, 'error');
            }

            $query = self::$paymentModel::query()
                ->where('user_id', $userId);

            if ($start_date && $end_date) {
                if ($end_date > $start_date) {
                    $query->whereBetween('created_at', [
                        $start_date . ' 00:00:00',
                        $end_date . ' 23:59:59'
                    ]);
                } else {
                    $query->whereDate('created_at', $start_date);
                }
            }

            $data = $query
                ->orderBy('created_at', 'desc')
                ->get();

            if ($data->isEmpty()) {
                return messageResponse('Data not found...', [], 404, 'error');
            }

            return entityResponse($data);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }



    }

  


}



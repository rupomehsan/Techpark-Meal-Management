<?php

namespace App\Modules\Management\ReportManagement\Controller;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller as ControllersController;
use App\Modules\Management\ReportManagement\Actions\UserData;
use App\Modules\Management\ReportManagement\Actions\DailyAllData;
use App\Modules\Management\ReportManagement\Actions\UsersAllData;
use App\Modules\Management\ReportManagement\Actions\MonthlyAllData;

use App\Modules\Management\ReportManagement\Actions\MonthlyDetails;
use App\Modules\Management\ReportManagement\Actions\MonthlyDetailsInvoice;

class Controller extends ControllersController
{

    public function daily()
    {
        $data = DailyAllData::execute();
        return $data;
    }

    public function monthly()
    {
        $data = MonthlyAllData::execute();
        return $data;
    }

    public function monthlyDetails($month)
    {
        $data = MonthlyDetails::execute($month);
        return $data;
    }

    public function monthlyDetailsInvoice($month)
    {
        $data = MonthlyDetailsInvoice::execute($month);
        // dd('invoice called...', $data);
        // if (!is_array($data)) {
        //     $data = [];
        // }
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();

        // $pdf = Pdf::loadView('MonthlyDetailsInvoice', $data)->setPaper('a4');
        // return $pdf->download('Invoice.pdf');
        // return $data;


    }

    public function downloadInvoice($month)
    {
        $data = DownloadInvoice::execute($month);
        return $data;
    }

    public function usersReport()
    {
        $data = UsersAllData::execute();
        return $data;
    }
    
    public function userReport($id)
    {
        $data = UserData::execute($id);
        return $data;
    }


}

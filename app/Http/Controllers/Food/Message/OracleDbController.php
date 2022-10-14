<?php

namespace App\Http\Controllers\Food\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class OracleDbController extends Controller
{
    //index
    public function test(){

        // Test database connection
        try {
          $check = DB::connection('oracle_db')->getPdo();
        } catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }

        dd($check);

       // DB::connection('oracle_db')

    }


    public function getInvoiceData(){
        $table = 'WA_FOOD_OUTLET_SALES';
        $oracleField= 'INVOICE_DATE';
        $start = '2022-07-20';
        $end = '2022-07-20';
        $cv_code = '182189';

        $responseQuery = DB::connection('oracle_db')->table($table)
        ->where('CUSTOMER_CODE', $cv_code)
        ->whereDate($oracleField,'<=', $end)
        ->whereDate($oracleField,'>=', $start)
        ->take(10)
        ->get()
        ->toArray();

        dd($responseQuery);

    }
}

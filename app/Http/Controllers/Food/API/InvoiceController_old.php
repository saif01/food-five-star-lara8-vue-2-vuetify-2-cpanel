<?php

namespace App\Http\Controllers\Food\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;
use DateTime;

class InvoiceController_old extends Controller
{
    // invoice
    public static function invoice($cv_code=null, $date=null){

        $table = 'WA_FOOD_OUTLET_SALES';
        $oracleField= 'INVOICE_DATE';

        

        //$cv_code = '182389';
        // $cv_code = '182189';
        // $date = '2022-07-20';
        // $start = '2022-07-20';
        // $end = '2022-07-20';
       

        // $responseQuery = DB::connection('oracle_db')->table($table)
        // ->where('CUSTOMER_CODE', $cv_code)
        // ->whereDate($oracleField,'<=', $end)
        // ->whereDate($oracleField,'>=', $start)
        // ->get();

        $data =  self::GetInvoiceByCVcodeDate($cv_code, $date);

        return $data;
    }



    // GetInvoiceByCVcodeDate
    public static function GetInvoiceByCVcodeDate($cv_code=null, $date=null){
        if($cv_code && $date){

            $table = 'WA_FOOD_OUTLET_SALES';
            $oracleField= 'INVOICE_DATE';
            // $start = '2022-07-20';
            // $end = '2022-07-20';
            // $cv_code = '182189';

            // All Fields geting fields data samples
            // +"INVOICE_DATE": "2022-09-05 00:00:00"
            // +"INVOICE_NO": "511122090449"
            // +"CUSTOMER_CODE": "182189"
            // +"PRODUCT_CODE": "9001001066"
            // +"QUANTITY": "11"
            // +"WEIGHT": "14.3"
            // +"AMOUNT": "6314.57"
            // +"VAT": "947.19"
            // +"NET_AMOUNT": "7261.76"
        

            $responseQuery = DB::connection('oracle_db')->table($table)
           ->where('CUSTOMER_CODE', $cv_code)
            ->whereDate($oracleField, $date)
            //->whereDate($oracleField,'<=', $end)
            //->whereDate($oracleField,'>=', $start)
            ->get()
            ->toArray();

            // dd( $responseQuery);

            //$resultData = self::ManualGroupByKey('INVOICE_NO',  $responseQuery);

            $resultData = self::ManualGroupByKeySum('INVOICE_NO',  $responseQuery);

            return $resultData;

        }

        return false;
    }



    // Manual Group By Key with Sum
    public static function ManualGroupByKeySum($key_name='INVOICE_NO', $data) {
        $groups = [];
        $details = [];
        // $groups = array();
        foreach ($data as $item) {
            $item = (array) $item;
            $key = $item[$key_name];
            if (!array_key_exists($key, $groups)) {
                $groups[$key] = array(
                    'INVOICE_DATE'  => $item['INVOICE_DATE'],
                    'INVOICE_NO'    => $item['INVOICE_NO'],
                    'CUSTOMER_CODE' => $item['CUSTOMER_CODE'],
                    'AMOUNT'        => $item['NET_AMOUNT'],
                );
            } else {
                $groups[$key]['AMOUNT'] = $groups[$key]['AMOUNT'] + $item['NET_AMOUNT'];
            }
            $groups[$key]['DATA'][] = ['PRODUCT_CODE' => $item['PRODUCT_CODE'],
                                        'QUANTITY'    => $item['QUANTITY']];
        }


        // dd( $groups);
        return $groups;
    }


    // Manual Group By Key
    public static function ManualGroupByKey($key_name='INVOICE_NO', $data) {
        $groups = [];

        foreach($data as $val) {
           $val = (array) $val;

            //dd( $val, $val['INVOICE_DATE']);
            if(array_key_exists($key_name, $val)){
                $groups[$val[$key_name]][] = $val;
            }else{
                $groups[""][] = $val;
            }
        }
        return $groups;
    }


}

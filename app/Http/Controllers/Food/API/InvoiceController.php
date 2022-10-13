<?php

namespace App\Http\Controllers\Food\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;
use DateTime;
use App\Models\Food\SmartsoftInvoice;

class InvoiceController extends Controller
{
    // invoice
    public static function invoice($cv_code=null, $date=null){

        //$cv_code = '182389';
        // $cv_code = '182189';
        // $date = '2022-07-20';
        // $start = '2022-07-20';
        // $end = '2022-07-20';
       
        $data =  self::GetInvoiceByCVcodeDateManual($cv_code, $date);

        return $data;
    }



    // GetInvoiceByCVcodeDateManual
    public static function GetInvoiceByCVcodeDateManual($cv_code=null, $date=null){
        if($cv_code && $date){

            $responseQuery = SmartsoftInvoice::where('CUSTOMER_CODE', $cv_code)
            ->whereDate('INVOICE_DATE', $date)
            //->whereDate('INVOICE_DATE','<=', $end)
            //->whereDate('INVOICE_DATE','>=', $start)
            ->selectRaw('INVOICE_DATE, INVOICE_NO, CUSTOMER_CODE, sum(NET_AMOUNT) as SumAMOUNT')
            ->groupBy('INVOICE_NO')
            ->get()
            ->toArray();

            return $responseQuery;
          
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

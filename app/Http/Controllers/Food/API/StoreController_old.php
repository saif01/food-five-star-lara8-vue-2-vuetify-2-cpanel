<?php

namespace App\Http\Controllers\Food\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;
use DateTime;
use App\Models\Food\SmartsoftInvoice;
use App\Models\Food\ProductOrder;
use App\Models\Food\OrderSummary;
use App\Models\Food\Order;
use Illuminate\Support\Arr;
use App\Http\Controllers\Food\CommonController;
use Auth;

class StoreController_old extends Controller
{
    public function getInvoiceData(){
        $table = 'WA_FOOD_OUTLET_SALES';
        $oracleField= 'INVOICE_DATE';
        $start = '2022-07-20';
        $end = '2022-07-20';
        $cv_code = '182189';

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
        //->where('CUSTOMER_CODE', $cv_code)
        // ->whereDate($oracleField, $date)
        ->whereDate($oracleField,'<=', $end)
        ->whereDate($oracleField,'>=', $start)
        ->get()
        ->toArray();


        $newArray = [];

        foreach ($responseQuery as $key => $value) {
            $newArray[] = [
                "INVOICE_DATE" => $value->INVOICE_DATE,
                "INVOICE_NO" => $value->INVOICE_NO,
                "CUSTOMER_CODE" => $value->CUSTOMER_CODE,
                "PRODUCT_CODE" => $value->PRODUCT_CODE,
                "QUANTITY" => $value->QUANTITY,
                "WEIGHT" => $value->WEIGHT,
                "AMOUNT" => $value->AMOUNT,
                "VAT" => $value->VAT,
                "NET_AMOUNT" => $value->NET_AMOUNT,
                "created_at" => Carbon::now()->toDateTimeString(),
                "updated_at" => Carbon::now()->toDateTimeString(),

            ];
        }


        $chunk_array = array_chunk($newArray, 500);

        foreach ($chunk_array as $key => $value) {

            SmartsoftInvoice::insert($value);

        }

    }


    

    public function invoiceSync($startDate = null, $endDate = null){
       
        
        // dd($startDate, $endDate);

        $table = 'WA_FOOD_OUTLET_SALES';
        $oracleField= 'INVOICE_DATE';

        if(!empty($startDate) && !empty($endDate)){
            $start = $startDate;
            $end = $endDate;
        }else{

            $start = Carbon::now()->toDateString();
            $end = Carbon::now()->toDateString();

        }

        


        if($start && $end){

            
            // get all order data
            $responseQuery = DB::connection('oracle_db')->table($table)
            //->where('CUSTOMER_CODE', $cv_code)
            // ->whereDate($oracleField, $date)
            ->whereDate($oracleField,'<=', $end)
            ->whereDate($oracleField,'>=', $start)
            ->get()
            ->toArray();

            if($responseQuery){
                $delete_status = SmartsoftInvoice::whereDate($oracleField,'<=', $end)
                ->whereDate($oracleField,'>=', $start)
                ->delete();

                
                $success = self::storeInvoiceData($responseQuery, $start, $end);

                if($success){
                    return response()->json(['Successfull'=>'Success', 'msg'=> 'Data Sync from '. $start . ' to ' . $end .'', 'icon'=>'success'],200);
                }else{
                    return response()->json(['title'=>'Error', 'msg'=> 'Data could not sync', 'icon'=>'error'],200);
                }
                

                
            }else{
                return response()->json(['title'=>'Error', 'msg'=> 'Smartsoft Invoice data not found between '. $start . ' and ' . $end .'', 'icon'=>'error'],200);
            }
        }else{
            return response()->json(['title'=>'Error', 'msg'=> 'Parameter Error', 'icon'=>'error'],200);

        }
        
    }

    public static function storeInvoiceData($responseQuery, $start, $end){

       //dd($responseQuery, $start, $end);

 
        $newArray = [];
        if($responseQuery){

            // convert object to array
            foreach ($responseQuery as $key => $value) {
                $newArray[] = [
                    "INVOICE_DATE" => $value->INVOICE_DATE,
                    "INVOICE_NO" => $value->INVOICE_NO,
                    "CUSTOMER_CODE" => $value->CUSTOMER_CODE,
                    "PRODUCT_CODE" => $value->PRODUCT_CODE,
                    "QUANTITY" => $value->QUANTITY,
                    "WEIGHT" => $value->WEIGHT,
                    "AMOUNT" => $value->AMOUNT,
                    "VAT" => $value->VAT,
                    "NET_AMOUNT" => $value->NET_AMOUNT,
                    "created_at" => Carbon::now()->toDateTimeString(),
                    "updated_at" => Carbon::now()->toDateTimeString(),

                ];
            }

        }

        

        // chucnk object into array 
        $chunk_array = array_chunk($newArray, 500);

        if($chunk_array){

            foreach ($chunk_array as $key => $value) {
                // store in db
                $status = SmartsoftInvoice::insert($value);

            }
            //dd($status);
            if($status){
                
                // store in order table
                $storeStatus = self::storeOrderTableData($start, $end);

                dd($status);

                return $status;
                
                
            }


        }


        
        

    }

    public static function storeOrderTableData($start, $end){

        $smartData = SmartsoftInvoice::with('product:id,product_code,tp', 'outlet:id,cv_code')->whereDate('INVOICE_DATE','<=', $end)
            ->whereDate('INVOICE_DATE','>=', $start)
            ->get()
            ->groupBy(['INVOICE_DATE', 'CUSTOMER_CODE'])
            ->toArray();

        // get all the order number from order summury table between two given dates [$start, $end], then fetch order_number from order summury table. according to the order number delete orders from orders table and then delete from order summury table //

        $getOrderSummuryOrderNumber = OrderSummary::whereDate('order_date','<=', $end)
            ->whereDate('order_date','>=', $start)
            ->select('order_number')
            ->get()
            ->toArray();

        $orderNumebr = Arr::flatten($getOrderSummuryOrderNumber);

        $deleteOrder = Order::whereIn('order_number', $orderNumebr)->delete();
        
        $deleteOrderSummury = OrderSummary::whereIn('order_number', $orderNumebr)->delete();

        // insert into order table

        foreach ($smartData as $valueKey => $value) {
            
            foreach ($value as $itemKey => $item) {

                // generate running number
                $order_number = CommonController::RunningNumberGenerate($itemKey);
                $totalPrice = 0;
                //dd($item);

                // dd($item[0], $valueKey, $order_number, $item[0]['CUSTOMER_CODE'], $totalPrice);

                foreach ($item as $key => $items) {

                    // according to our database, if porduct id and outlet id is present then store for this outlet and product will run
                    if($items['product'] && $items['outlet']){

                        $items = (object) $items;

                        $data = new Order();
                        
                        $data->order_number   = $order_number;
                        $data->product_id     = $items->product['id'];
                        $data->outlet_id      = $items->outlet['id'];
                        $data->cv_code        = $items->CUSTOMER_CODE;
                        $data->quantity       = $items->QUANTITY;


                        $data->price          = $items->product['tp'];
                        $totalPrice           += $items->product['tp'] * $items->QUANTITY;
                        

                        $data->status         = 1;
                        //$data->created_by     = Auth::user()->id;
                        $success              = $data->save();

                    }

                }


                if($totalPrice > 0){
                    // now store into summury table

                    $dataSummary = new OrderSummary();

                    $dataSummary->order_date        = $valueKey;
                    $dataSummary->order_number      = $order_number;
                    $dataSummary->cv_code           = $item[0]['CUSTOMER_CODE'];
                    $dataSummary->total_price       = $totalPrice;
                    $dataSummary->status            = 1;
                    //$dataSummary->created_by        = Auth::user()->id;
                    $success                        = $dataSummary->save();

                    // if($status){
                    //     return $status;
                    // }else{
                    //     return 'Summury and Order Store Error';
                    // }
                    
                }

                

            }
        }

        

    }
}

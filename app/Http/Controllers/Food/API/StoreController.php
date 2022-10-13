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

class StoreController extends Controller
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

        dd($responseQuery);


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

        dd($chunk_array); 

    }


    

    public function invoiceSync($startDate = null, $endDate = null){   

        if(!empty($startDate) && !empty($endDate)){
            $start = $startDate;
            $end = $endDate;
        }else{

            $start = Carbon::now()->toDateString();
            $end = Carbon::now()->toDateString();

        }


        if($start && $end){

            $startFm = new DateTime($start);
            $endFm = new DateTime($end);
            $interval = $startFm->diff($endFm);
            $days = $interval->format('%a');
        
            $interval=7;
            
            for ($i=$interval; $i <= $days; $i += $interval) { 
                // check interval
                if($days >= $interval) {
                    // Set new End  by adding interval days
                    $newend = date('Y-m-d', strtotime($start. '+'.$interval.' day'));
                    
                    // **************** start oracle to mysql data store **************
                    self::DataDeleteStore($start, $end);
                    // **************** end oracle to mysql data store **************
                    $start = $newend;
                }
            }
            self::DataDeleteStore($start, $end);

            
        }
                    
        return response()->json(['title'=>'Successfull', 'msg'=> 'Data Sync from '. $start . ' to ' . $end .'', 'icon'=>'success'],200);
               
    }


    public static function DataDeleteStore($start, $end){

        $table = 'WA_FOOD_OUTLET_SALES';
        $oracleField= 'INVOICE_DATE';

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

        }

        return true;

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
            if($status){
                // store in order table
                $storeStatus = self::storeOrderTableData($start, $end);
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

        $getOrderSummury = OrderSummary::whereDate('order_date','<=', $end)
            ->whereDate('order_date','>=', $start)
            ->select('order_number')
            ->get()
            ->toArray();
        // Make one array
        $orderNumber = Arr::flatten($getOrderSummury);
        // Delete Related order
        $deleteOrder = Order::whereIn('order_number', $orderNumber)->delete();

        

        // insert into order table
        foreach ($smartData as $valueKey => $value) {
            
            foreach ($value as $itemKey => $item) {
                $totalPrice = 0;
                $newArray = [];
                
                foreach ($item as $key => $items) {

                    // according to our database, if porduct id and outlet id is present then store for this outlet and product will run 
                    if($items['product'] && $items['outlet']){

                        $items = (object) $items;
                        $findOrderNumber = OrderSummary::whereDate('order_date', $valueKey)->where('cv_code', $items->CUSTOMER_CODE)->select('order_number')->first();

                        if($findOrderNumber){
                            $newArray[] = [
                                "order_number" =>  $findOrderNumber->order_number,
                                "product_id" => $items->product['id'],
                                "outlet_id" => $items->outlet['id'],
                                "cv_code" => $items->CUSTOMER_CODE,
                                "quantity" => $items->QUANTITY,
                                "price" => $items->product['tp'],
                                "status" => 1,
                                "updated_at" => Carbon::now()->toDateTimeString(),
                            ];
                            $totalPrice           += $items->product['tp'] * $items->QUANTITY;
                        }

                        
                    }

                }
                
                
                // Save Order table
                Order::insert($newArray);
                $newArray = [];


                if($totalPrice > 0){
                    // now store into summury table
                    
                    $prevOrder = OrderSummary::where('order_number', $findOrderNumber->order_number)->first();

                    $prevOrder->total_price       = $totalPrice;
                    $prevOrder->invoice_number    = $item[0]['INVOICE_NO'];
                    $prevOrder->invoice_time      = $item[0]['INVOICE_DATE'];
                    $prevOrder->updated_at        = Carbon::now()->toDateTimeString();
                    $success                      = $prevOrder->save();
                        
                }

                

            }
        }

        

    }
}

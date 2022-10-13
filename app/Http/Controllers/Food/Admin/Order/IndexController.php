<?php

namespace App\Http\Controllers\Food\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\AdminOrder;
use Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Food\CommonController;
use App\Models\Food\OrderSummary;
use App\Models\Food\Order;
use App\Models\Food\Outlet;
use App\Models\Food\ProductOrder;
use App\Http\Controllers\Food\API\InvoiceController;

use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;

class IndexController extends Controller
{

    //index
    // public function index(){
    //     $allData = $this->getData('index');

    //     return response()->json($allData, 200);
        
    // }
    
    public function index(){

        // Get all CV Code by assign zone
        $cv_code_array = CommonController::GetCVcodeByAssignedZone();


        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $search_field   = Request('search_field', '');
        $start_date     = Request('start_date', null);
        $end_date       = Request('end_date', null);
        $by_zone        = Request('by_zone', null);
        $by_type        = Request('by_type', null);

        $allDataQuery = OrderSummary::with('all_orders', 'all_orders.product_details', 'outlet_details', 'managerby:id,name', 'adminby:id,name'); 

        if(!empty($by_type) && $by_type != 'All'){

            if( $by_type == 'appm'){
                // Manager Approved
                $allDataQuery->whereNotNull('manager_approve');
            }
            if( $by_type == 'app'){
                // Approved
                $allDataQuery->whereNotNull('manager_approve')->whereNotNull('admin_approve');

            }elseif( $by_type == 'notapp' ){
                // Not Approved
                $allDataQuery->whereNull('manager_approve')->whereNull('admin_approve');

            }elseif( $by_type == 'pmtnotcom' ){
                // Payment Not Complated
                 $allDataQuery->whereNull('payment_prove');
            }elseif( $by_type == 'pmtcom' ){
                // Payment Complated
                 $allDataQuery->whereNotNull('payment_prove');
            }
        }
         
        if(!empty($by_zone) && $by_zone != 'All'){
            $allDataQuery->whereIn('cv_code', explode(",", $by_zone));
        }else{
            $allDataQuery->whereIn('cv_code', $cv_code_array);
        }

        if($start_date){
            $allDataQuery->whereDate('order_date', $start_date);
        }

       
        
        // Search
        if(!empty($search_field) && $search_field != 'All'){
            $val = trim(preg_replace('/\s+/' ,' ', $search));
            $allDataQuery->where($search_field, 'LIKE', '%'.$val.'%');
        }else{
            $allDataQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) );
        }

        $allData = $allDataQuery->orderBy($sort_field, $sort_direction)->paginate($paginate);

        // if($val == 'index'){
        //     $allData = $allData->paginate($paginate);
        // }else{
        //     $allData = $allData->get();
        // }


        // Append Filed Data
        foreach($allData as $item){
            $cv_code = $item->cv_code;
            $order_date = $item->order_date;
            $invoice = null;
            if( $cv_code && $order_date ){
               $invoice =  InvoiceController::invoice($cv_code, $order_date); 

            }
            // Append
            $item->invoice =  $invoice;
        }

        return response()->json($allData, 200);

    }
    

    //export 
    public function export_data_order(Request $request){

        $search         = Request('search', '');
        $date           = Request('start_date', null);
        $zone           = Request('by_zone', null);
        $by_type        = Request('by_type', null);

         // get all cv code in a specific zone
        $allCvCodeinZone = Outlet::select('cv_code')->where('zone_id', $zone)->get();
        
        $currentCvCodeArray = [];
        foreach ($allCvCodeinZone as $item) {
            array_push($currentCvCodeArray, $item->cv_code );
        }

        $allQuery = Order::with('order_summary:order_date,id,order_number', 'order_summary.outlet_details', 'product_details:type,id', 'outlet:shop_name,id')
        ->whereHas('order_summary', function ($query) use($date) {
            $query->where('order_date', $date);
        });

        if($zone != 'All'){
            $allQuery->whereIn('cv_code', $currentCvCodeArray);
        }
        $allData = $allQuery
            ->select('quantity','price','cv_code','outlet_id','order_number','product_id')
            ->get()
            ->groupBy('cv_code')
            ->take(1)
            ->toArray();

        //dd($data);

        $productNames = ProductOrder::orderBy("id", "asc")
            ->whereNull('deleted_temp')
            ->where('status', '1')
            ->select('type')
            ->get()
            ->toArray();



        $allData +=  [
            'date'  => '('. date('d F, Y', strtotime(Request('start_date'))) .') ',
            'name'  => $productNames

        ];
        // dd($allData);

        return Excel::download(new OrderExport( (object) $allData), 'product-' . time() . '.xlsx');
    }


    //store order
    public function store(Request $request){

        $items      = $request->items;
        $cv_code    = $request->cv_code;
        $order_date = $request->order_date;

        $checkOrderHaveOrNot = CommonController::checkSameDaySameShopOrder($order_date, $cv_code);

        if($checkOrderHaveOrNot){

            return response()->json(['msg'=>'This shops order has already been placed. You can modify it.', 'icon'=>'warning']);

        }else{
            $shop_info  = Outlet::where('cv_code', $cv_code)->first();
            $order_number = CommonController::RunningNumberGenerate($cv_code);

        
            $totalPrice = 0;
            
            foreach ($items as $key => $value) {
                $value = (object) $value;

                $data = new Order();
                
                $data->order_number   = $order_number;
                $data->product_id     = $value->id;
                $data->outlet_id      = $shop_info->id;
                $data->cv_code        = $cv_code;
                $data->quantity       = is_int($value->quantity_order) ? $value->quantity_order : 1;



                $data->price          = $value->tp;
                $totalPrice           += $value->tp * $value->quantity_order;
                
                
                $data->status         = 1;
                $data->created_by     = Auth::user()->id;
                $success              = $data->save();
                
            }


            $dataSummary = new OrderSummary();

            $dataSummary->order_date = $order_date;
            $dataSummary->order_number = $order_number;
            $dataSummary->cv_code    = $cv_code;
            $dataSummary->total_price= $totalPrice;
            $dataSummary->status     = 1;
            $dataSummary->created_by = Auth::user()->id;
            $success                 = $dataSummary->save();

            
            if($success){
                return response()->json(['msg'=>'Order has been placed', 'icon'=>'success'], 200);
            }else{
                return response()->json([
                    'msg' => 'Order Could Not Place At This Moment !!'
                ], 422);
            }
        }

        

    }


    //update order
    public function update(Request $request){
        
        $items      = $request->items;
        $cv_code    = $request->cv_code;
        $order_date = $request->order_date;
        $previous_order_number = $request->order_number;

        $checkSameDayOrderHaveOrNot = CommonController::checkModifyOrderSameDaySameShop($previous_order_number, $cv_code, $order_date);
        if($checkSameDayOrderHaveOrNot){

            $checkOrderHaveOrNot = CommonController::checkSameDaySameShopOrder($order_date, $cv_code);

            if($checkOrderHaveOrNot){
                return response()->json(['msg'=>'This shops order has already been placed. You can modify it.', 'icon'=>'warning']);
            }
        }

        $shop_info  = Outlet::where('cv_code', $cv_code)->first();

        $order_number = CommonController::RunningNumberGenerate($cv_code);

        $delete_previous_order = Order::where('order_number', $previous_order_number)->get();
        
        $deleted_row_id = [];
        // push the ids with same order no
        foreach ($delete_previous_order as $item) {
            array_push($deleted_row_id, $item->id);
        }
        // delete data
        Order::whereIn('id', $deleted_row_id)->delete();
       
        $totalPrice = 0;
        
        foreach ($items as $key => $value) {
            $value = (object) $value;

            $data = new Order();
            
            $data->order_number   = $previous_order_number;
            $data->product_id     = $value->id;
            $data->outlet_id      = $shop_info->id;
            $data->cv_code        = $cv_code;
            $data->quantity       = is_int($value->quantity_order) ? $value->quantity_order : 1;

            $data->price      = $value->tp;
            $totalPrice       += $value->tp * $value->quantity_order;
        
            $data->status         = 1;
            $data->created_by     = Auth::user()->id;
            $success              = $data->save();
            
        }

        // Find Order Summary Data
        $dataSummary = OrderSummary::where('order_number', $previous_order_number)->first();

        if(!$dataSummary){
            return response()->json(['msg'=>'Order Not Found !!', 'icon'=>'warning'], 200);
        }
        $dataSummary->order_date = $order_date;
        $dataSummary->order_number = $previous_order_number;
        $dataSummary->cv_code    = $cv_code;
        $dataSummary->total_price= $totalPrice;
        $dataSummary->status     = 1;
        $dataSummary->created_by = Auth::user()->id;
        $success                 = $dataSummary->save();

        
        if($success){
            return response()->json(['msg'=>'Order Modified Successfully &#128513;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Order Could Not Place At This Moment !!'
            ], 422);
        }

    }

    public function order_details(Request $request){
        $allData = Order::with('product_details')->where('order_number', $request->order_number)->get();

        return response()->json($allData, 200);
    }

    // food_item_with_modify_order
    public function food_item_with_modify_order(){

        $search   = Request('search', '');
        $type     = Request('sort_type', '');
        $order_number = Request('order_number');

        $allQuery = ProductOrder::with('order_type')->orderBy("id", "asc")
            ->whereHas('order_type', function ($query) {
                $query->where('status', 1);
            })
            ->whereNull('deleted_temp')
            ->where('status', '1');

        if(!empty($type) && $type != 'All'){
            $allQuery->where('category', $type );
        }

        $allData =  $allQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) )->get()->toArray();

        // ordersDetails
        $allOrders = Order::where('order_number', $order_number)->where('status', 1)->get()->toArray();


        $newData = [];
        $previousorder = [];

        foreach ($allData as  $value) {

            foreach ($allOrders as $value2) {
                
                if($value2['product_id'] === $value['id']){

                    $opt = [
                        'quantity_order' =>  is_int($value2['quantity']) ? $value2['quantity'] : 1
                    ];
                    
                    //Merge array 
                    $value = array_merge($value, $opt);

                    array_push($previousorder, $value);

                }
            }

            // Push data 
            array_push($newData, $value);
            
        }

        $groupByData = self::group_by('category',$newData);

    
        return response()->json(['allData'=> $groupByData, 'previousorder'=>$previousorder ], 200);


    }



    // food_item_list
    public function food_item_list(){
        $search   = Request('search', '');
        $type     = Request('sort_type', '');

        $allQuery = ProductOrder::with('order_type')->orderBy("id", "asc")
            ->whereHas('order_type', function ($query) {
                $query->where('status', 1);
            })
            ->whereNull('deleted_temp')
            ->where('status', 1);
                

        if(!empty($type) && $type != 'All'){
            $allQuery->where('category', $type );
        }
        $allData =  $allQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) )->get()->groupBy('category');

        return response()->json($allData, 200);
    }

    // group_by
    public function group_by($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }


    // status
    public function status($id){

        $data       =  OrderSummary::find($id);
        if($data){

           $status = $data->status;
           
            if($status == 1){
                $data->status = null;
            }else{
                $data->status = 1;
            }
            $success    =  $data->save();
            return response()->json('success', 200);

        }

    }





    public function sku(){
        $zone         = Request('zone', '');
        $start_date   = Request('start_date', '');


        $cv_code_array = CommonController::GetCVcodeByAssignedZone();

        $orderAllQuery = Order::with('product_order_table.order_type')
            ->whereHas('order_summary', function ($query) use($start_date) {
                $query->where('order_date', $start_date);
            });


            if(!empty($zone) && $zone != 'All'){
                $orderAllQuery->whereIn('cv_code', explode(",", $zone));
            }else{
                $orderAllQuery->whereIn('cv_code', $cv_code_array);
            }

        $allOrder = $orderAllQuery->selectRaw('*, sum(quantity) as quantity')->groupBy('product_id')->get();

        

        $newData = [];
        foreach ($allOrder as $key => $value) {

            if($value->product_order_table){
                if ($value->product_order_table->weight_type == 'kg') {
                    $new = [
                        'weight' => $value->quantity  * ($value->product_order_table->weight * 1000),
                        'quantity' => $value->quantity,
                        'product_name' => $value->product_order_table->type ?? 'N/A',
                        'product_id' => $value->product_order_table->id ?? 'N/A',
                        'product_type' => $value->product_order_table->order_type->name_en ?? 'N/A',
                        'product_code' => $value->product_order_table->product_code ?? 'N/A',
                        'product_unit_price' => $value->product_order_table->tp ?? 'N/A',
                        'price' => $value->quantity * $value->price,
                    ];
                    
                }else{
                    $new = [
                        'weight' => $value->quantity  * $value->product_order_table->weight,
                        'quantity' => $value->quantity,
                        'product_name' => $value->product_order_table->type ?? 'N/A',
                        'product_id' => $value->product_order_table->id ?? 'N/A',
                        'product_type' => $value->product_order_table->order_type->name_en ?? 'N/A',
                        'product_code' => $value->product_order_table->product_code ?? 'N/A',
                        'product_unit_price' => $value->product_order_table->tp ?? 'N/A',
                        'price' => $value->quantity * $value->price,
                    ];
                    
                }

                array_push($newData, $new);

            }

            
        }


        $totalWeight = array_sum(array_column($newData, 'weight'));
        $totalPrice = array_sum(array_column($newData, 'price'));
        $totalQuantity = array_sum(array_column($newData, 'quantity'));

        return response()->json(['allData'=>$newData, 'totalWeight'=> $totalWeight, 'totalPrice'=>$totalPrice, 'totalQuantity'=>$totalQuantity],200);
    }



    //InvoiceOrderModify
    public static function InvoiceOrderModify($data, $order_number, $outlet_id, $cv_code){

        $productCodeArray = [];

        foreach ($data as $key => $value) {
            
            foreach ($value['DATA'] as $key => $value2) {
                // push all product code
                array_push($productCodeArray, $value['DATA'], $value2['PRODUCT_CODE']);

            }
            

        }

        // get all order product data against product code
        $getOrderWithProductCode =  ProductOrder::whereIn('product_code', $productCodeArray)->select('id', 'product_code', 'tp', 'quantity')->get();


        foreach ($getOrderWithProductCode as $key => $allItem) {
           
            foreach ($data as $key => $value) {
            
                foreach ($value['DATA'] as $key => $value2) {

                    if($value2['PRODUCT_CODE'] == $allItem->product_code){
                        

                        $allItem->invoice_quantity =  $value2['QUANTITY'];
                    }
                }
            } 
        }

        // modify order and order summury table against order number

        Order::where('order_number', $order_number)->delete();
       
        $totalPrice = 0;
        
        foreach ($getOrderWithProductCode as $key => $value) {
            $value = (object) $value;

            $data = new Order();
            
            $data->order_number   = $order_number;
            $data->product_id     = $value->id;
            $data->outlet_id      = $outlet_id;
            $data->cv_code        = $cv_code;
            $data->quantity       = $value->invoice_quantity;

            $data->price      = $value->tp;
            $totalPrice       += $value->tp * $value->invoice_quantity;
        
            $data->status         = 1;
            $data->created_by     = Auth::user()->id;
            $success              = $data->save();
            
        }

        // Find Order Summary Data
        $dataSummary = OrderSummary::where('order_number', $order_number)->first();

        $dataSummary->total_price = $totalPrice;
        $dataSummary->created_by  = Auth::user()->id;
        $success                  = $dataSummary->save();




    }


    
}

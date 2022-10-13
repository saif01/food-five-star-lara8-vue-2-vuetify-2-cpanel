<?php

namespace App\Http\Controllers\Food\Operator\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Food\Order;
use App\Models\Food\Outlet;
use App\Http\Controllers\Food\CommonController;
use App\Models\Food\ProductSale;
use App\Models\Food\ProductType;
use App\Models\Food\OrderSummary;
use App\Models\Food\ProductOrder;
use App\Models\Food\Sale;
use App\Http\Controllers\Food\API\InvoiceController;

class IndexController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $search_field   = Request('search_field', '');
        $start_date     = Request('start_date', null);
        $end_date       = Request('end_date', null);

        $allDataQuery = OrderSummary::with('all_orders:id,order_number,product_id,outlet_id,cv_code,quantity,price,status', 'all_orders.product_details:id,product_code,type,type_bn,category,packaging_type,weight,weight_type,quantity,tp,status')
        ->where('status', 1)
        ->select('id','order_date','order_number','cv_code','total_price','payment_amount','payment_prove','payment_doc','manager_approve','admin_approve','invoice_number','invoice_time', 'status', 'created_at')
        ->where('cv_code', Auth::user()->cv_code);

        if($start_date){
            $allDataQuery->whereDate('order_date','>=', $start_date);
        }
        if($end_date){
            $allDataQuery->whereDate('order_date','<=', $end_date);
        }

        // Search
        if(!empty($search_field) && $search_field != 'All'){
            $val = trim(preg_replace('/\s+/' ,' ', $search));
            $allDataQuery->where($search_field, 'LIKE', '%'.$val.'%');
        }else{
            $allDataQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) );
        }

        $allData = $allDataQuery->orderBy($sort_field, $sort_direction)
                ->paginate($paginate);

        
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

        //dd($allData);

                

        return response()->json($allData, 200);
    }


    //store order
    public function store(Request $request){

        $items      = $request->items;
        $order_date = $request->order_date;
        

        $checkOrderHaveOrNot = CommonController::checkSameDaySameShopOrder($order_date, Auth::user()->cv_code);

        if($checkOrderHaveOrNot){

            if($checkOrderHaveOrNot){
                return response()->json(['title'=>'Warning','title_bn'=>'সতর্কতা', 'msg'=>'Your order has already been placed. You can modify it.', 'icon'=>'warning', 'msg_bn'=>'আপনার অর্ডার ইতিমধ্যে স্থাপন করা হয়েছে । আপনি এটি সংশোধন করতে পারেন ।' ], 200);
            }

        }

        $shop_info  = Outlet::where('cv_code', Auth::user()->cv_code)->first();
        $lastOrderNumber = Order::where('cv_code', Auth::user()->cv_code)->orderBy('id','desc')->select('order_number')->first();

        $order_number = CommonController::RunningNumberGenerate(Auth::user()->cv_code);


       
        $totalPrice = 0;
        
        foreach ($items as $key => $value) {
            $value = (object) $value;

            $data = new Order();
            
            $data->order_number   = $order_number;
            $data->product_id     = $value->id;
            $data->outlet_id      = $shop_info->id;
            $data->cv_code        = Auth::user()->cv_code;
            $data->quantity       = is_int($value->quantity_order) ? $value->quantity_order : 1;


            
            $data->price      = $value->tp;
            $totalPrice       += $value->tp * $value->quantity_order;
            
            
    
            $data->status         = 1;
            $data->created_by     = Auth::user()->id;
            $success              = $data->save();
            
        }


        $dataSummary = new OrderSummary();

        $dataSummary->order_date    = $order_date;
        $dataSummary->order_number  = $order_number;
        $dataSummary->cv_code       = Auth::user()->cv_code;
        $dataSummary->total_price   = $totalPrice;
        $dataSummary->status        = 1;
        $dataSummary->created_by    = Auth::user()->id;
        $success                    = $dataSummary->save();

        
        if($success){
            return response()->json(['title'=>'Successful', 'title_bn'=>'সফল', 'msg'=>'Order has been placed', 'icon'=>'success', 'msg_bn'=>'অর্ডার সম্পন্ন করা হয়েছে'], 200);
        }else{
            return response()->json([
                'msg' => 'Order Could Not Place At This Moment !!',
                'msg_bn' => 'অর্ডার এই মুহূর্তে স্থাপন করা যায়নি !!',
                'icon'=>'warning'
            ], 422);
        }

    }


    //update order
    public function update(Request $request){

        $items      = $request->items;
        $order_date = $request->order_date;
        $previous_order_number = $request->order_number;

        $checkSameDayOrderHaveOrNot = CommonController::checkModifyOrderSameDaySameShop($previous_order_number, Auth::user()->cv_code, $order_date);
        if($checkSameDayOrderHaveOrNot){

            $checkOrderHaveOrNot = CommonController::checkSameDaySameShopOrder($order_date, Auth::user()->cv_code);

            if($checkOrderHaveOrNot){
                return response()->json(['title'=>'Warning', 'title_bn'=>'সতর্কতা' ,'msg'=>'Your order has already been placed. You can modify it.', 'icon'=>'warning', 'msg_bn'=>'আপনার অর্ডার ইতিমধ্যে স্থাপন করা হয়েছে । আপনি এটি সংশোধন করতে পারেন ।' ], 200);
            }
        }

        $shop_info  = Outlet::where('cv_code', Auth::user()->cv_code)->first();

        $order_number = CommonController::RunningNumberGenerate(Auth::user()->cv_code);

        $delete_previous_order = Order::where('order_number', $previous_order_number)->get();
        
        $deleted_row_id = [];
        // push the ids with same order no
        foreach ($delete_previous_order as $item) {
            array_push($deleted_row_id, $item->id);
        }
        // delete data
        Order::whereIn('id', $deleted_row_id)->delete();
        //OrderSummary::where('order_number', $previous_order_number)->delete();
       
        $totalPrice = 0;
        
        foreach ($items as $key => $value) {
            $value = (object) $value;

            $data = new Order();
            
            $data->order_number   = $previous_order_number;
            $data->product_id     = $value->id;
            $data->outlet_id      = $shop_info->id;
            $data->cv_code        = Auth::user()->cv_code;
            $data->quantity       = is_int($value->quantity_order) ? $value->quantity_order : 1;

            
            $data->price      = $value->tp;
            $totalPrice       += $value->tp * $value->quantity_order;
            
            
    
            $data->status         = 1;
            $data->created_by     = Auth::user()->id;
            $success              = $data->save();
            
        }


        $dataSummary = OrderSummary::where('order_number', $previous_order_number)->first();

        $dataSummary->order_date    = $order_date;
        // $dataSummary->order_number  = $previous_order_number;
        $dataSummary->cv_code       = Auth::user()->cv_code;
        $dataSummary->total_price   = $totalPrice;
        $dataSummary->status        = 1;
        $dataSummary->created_by    = Auth::user()->id;
        $success                    = $dataSummary->save();

        
        if($success){
            return response()->json(['title'=>'Successful', 'title_bn'=>'সফল', 'msg'=>'Order has been updated', 'icon'=>'success', 'msg_bn'=>'অর্ডার আপডেট করা হয়েছে'], 200);
        }else{
            return response()->json([
                'msg' => 'Order Could Not Place At This Moment !!',
                'msg_bn' => 'অর্ডার এই মুহূর্তে স্থাপন করা যায়নি !!',
                'icon'=>'warning'
            ], 422);
        }

    }


    public function order_details(Request $request){
        $allData = Order::with('product_details:id,product_code,type,type_bn,category,packaging_type,weight,weight_type,quantity,tp,status')->where('order_number', $request->order_number)
        ->select('id','order_number','product_id','outlet_id','cv_code','quantity','price','status','created_at')
        ->get()
        ->toArray();

        return response()->json($allData, 200);
    }



    // food_item_with_modify_order
    public function food_item_with_modify_order(){

        $search   = Request('search', '');
        $type     = Request('sort_type', '');
        $order_number = Request('order_number', '');

        $allQuery = ProductOrder::with('order_type:id,name_en,name_bn,status')->orderBy("id", "asc")
            ->whereHas('order_type', function ($query) {
                $query->where('status', 1);
            })
            ->select('id', 'type', 'type_bn', 'category', 'quantity', 'remark', 'tp', 'weight', 'weight_type','status','image')
            ->whereNull('deleted_temp')
            ->where('status', 1);
                

        if(!empty($type) && $type != 'All'){
            $allQuery->where('category', $type );
        }

        $allData =  $allQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) )->get()->toArray();

        $orderData = Order::with('product_details:id,product_code,type,type_bn,category,packaging_type,weight,weight_type,quantity,tp,status')
        ->where('order_number', $order_number)
        ->select('id','order_number','product_id','outlet_id','cv_code','quantity','price','status','created_at')
        ->get()
        ->toArray();

        // dd($orderData );

        $newData = [];
        $previous_order = [];

        foreach ($allData as $value) {
            
            foreach ($orderData as $value2) {
                
                if($value['id'] == $value2['product_id']){

                    $quantityNew = [ 'quantity_order' => is_int($value2['quantity']) ? $value2['quantity'] : 1];
                    $value = array_merge($value, $quantityNew);

                    // prev order
                    array_push($previous_order, $value);
                    // dd(array_replace($item, $item2));
                }
            }

            array_push($newData, $value);
        }



        $finalData = self::group_by("category", $newData);

        return response()->json( ['food_items'=>$finalData,'previousorder' =>$previous_order,200]);

    }

    public function food_type(){

        $allData = ProductType::select('id','name_en','name_bn','status')->get()->toArray();

        return response()->json($allData, 200);

    }

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



    // get all order product data
    public function food_item_for_order(){

        $search   = Request('search', '');
        $type     = Request('sort_field', '');

        $allQuery = ProductOrder::with('order_type:id,name_en,name_bn,status')->orderBy("id", "asc")
            // ->whereHas('sale',function($q){
            //     $q->select('id', 'name_en', 'name_bn', 'image');
            // })
            ->whereHas('order_type', function ($query) {
                $query->where('status', 1);
            })
            ->select('id', 'type', 'type_bn', 'category', 'quantity', 'remark', 'tp', 'weight', 'weight_type','status', 'image')
            ->whereNull('deleted_temp')
            ->where('status', 1);

            
        if(!empty($type) && $type != 'All'){
            $allQuery->where('category', $type );
        }

        $allData =  $allQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) )->get()->groupBy('category');

        return response()->json($allData, 200);

    }


    

    
}

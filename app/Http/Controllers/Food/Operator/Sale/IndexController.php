<?php

namespace App\Http\Controllers\Food\Operator\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\Sale;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use App\Http\Controllers\Food\CommonController;
use App\Models\Food\SalesSummury;

class IndexController extends Controller
{
    //store
    public function store(Request $request){

        // last order as per cv code
        $lastSalesNumber = Sale::where('cv_code', Auth::user()->cv_code)->orderBy('id','desc')->select('sales_number')->first();
      
        if($lastSalesNumber){
            $sales_number = CommonController::salesRunningNumber(Auth::user()->cv_code, $lastSalesNumber->sales_number);
        }else{
            $sales_number = CommonController::salesRunningNumber(Auth::user()->cv_code);
        }
        
        

        $cart_items = $request->cart;
        $customer_info = (object) $request->customer_info;

        // for free_item
        $free_item_status = [];
        $free_item_id = '';
        // end free_item
        
        foreach ($cart_items as $key => $value) {
            $value = (object) $value;

            // for free_item
            if($value->free_item){
                array_push($free_item_status, $value->quantity);
                $free_item_id = $value->free_item;
            }
            // end free_item

            // create
            $data = new Sale();

            $data->cv_code          = Auth::user()->cv_code;
            $data->product_id       = $value->id;
            $data->quantity         = $value->quantity;

            $data->sales_number     = $sales_number;
            $data->created_by       = Auth::user()->id;
            $success                = $data->save();

        }

        // store for free item
        if(array_sum($free_item_status) > 0){
            $newData = new Sale();

            $newData->cv_code          = Auth::user()->cv_code;
            $newData->product_id       = $free_item_id;
            $newData->quantity         = array_sum($free_item_status);

            $newData->sales_number     = $sales_number;
            $newData->free_item        = 1; 
            $newData->created_by       = Auth::user()->id;
            $success                   = $newData->save();

        }




        // sales summury
        $total_price = [];
        foreach ($cart_items as $item) {
            $item = (object) $item;

            if($item->price_offer){
                array_push($total_price, ($item->quantity * $item->price_offer));
            }else{
                array_push($total_price, ($item->quantity * $item->price));
            }

        }

     
        $allData = new SalesSummury();
        

        $allData->sales_numb       = $sales_number;
        $allData->sales_date       = Carbon::now()->toDateTimeString();
        $allData->cv_code          = Auth::user()->cv_code;
        $allData->total_price      = array_sum($total_price);
        $allData->created_by       = Auth::user()->id;
        // custome details
        $allData->customer_number  = $customer_info->customer_number;
        $allData->customer_age     = $customer_info->customer_age;
        $allData->customer_name    = $customer_info->customer_name;
        $allData->customer_type    = $customer_info->customer_type;
        $success                   = $allData->save();
        
        
        

        
        if($success){
            return response()->json(['msg'=>'Order Place Successfully &#128513;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Order Could Not Place At This Moment !!'
            ], 422);
        }


    }


    //update
    public function update(Request $request){
        
        $cart_items = $request->cart;
        $customer_info = (object) $request->customer_info;


        // get all order with same sales number
        $allData = Sale::where('sales_number', $customer_info->sales_numb)->get();
        
        $updated_row_id = [];
        // push the ids with same order no
        foreach ($allData as $item) {
            array_push($updated_row_id, $item->id);
        }
        // delete data
        Sale::whereIn('id', $updated_row_id)->delete();
        

        // for free_item
        $free_item_status = [];
        $free_item_id = '';
        // end free_item



        foreach ($cart_items as $key => $value) {
            $value = (object) $value;


            // for free_item
            if($value->free_item){
                array_push($free_item_status, $value->quantity);
                $free_item_id = $value->free_item;
            }
            // end free_item



            // assing new data
            $data = new Sale();

            $data->cv_code             = Auth::user()->cv_code;
            $data->product_id          = $value->id;
            $data->quantity            = $value->quantity;
            
            $data->sales_number        = $customer_info->sales_numb;
            $data->created_by          = Auth::user()->id;
            $success                   = $data->save();
 
        }


        // store for free item
        if(array_sum($free_item_status) > 0){
            $newData = new Sale();

            $newData->cv_code          = Auth::user()->cv_code;
            $newData->product_id       = $free_item_id;
            $newData->quantity         = array_sum($free_item_status);

            $newData->sales_number     = $customer_info->sales_numb;
            $newData->free_item        = 1; 
            $newData->created_by       = Auth::user()->id;
            $success                   = $newData->save();

        }



        // sales summury
        $total_price = [];
        foreach ($cart_items as $item) {
            $item = (object) $item;

            if($item->price_offer){
                array_push($total_price, ($item->quantity * $item->price_offer));
            }else{
                array_push($total_price, ($item->quantity * $item->price));
            }

        }

     
        $allData = SalesSummury::where('sales_numb', $customer_info->sales_numb)->first();
        

        $allData->sales_date       = Carbon::now()->toDateTimeString();
        $allData->cv_code          = Auth::user()->cv_code;
        $allData->total_price      = array_sum($total_price);
        $allData->created_by       = Auth::user()->id;
        // custome details
        $allData->customer_number  = $customer_info->customer_number;
        $allData->customer_age     = $customer_info->customer_age;
        $allData->customer_name    = $customer_info->customer_name;
        $allData->customer_type    = $customer_info->customer_type;
        $success                   = $allData->save();
        
        if($success){
            return response()->json(['msg'=>'Order Updated Successfully &#128513;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Order Could Not Update At This Moment !!'
            ], 422);
        }

    }


}

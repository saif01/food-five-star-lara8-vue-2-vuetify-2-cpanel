<?php

namespace App\Http\Controllers\Food\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Food\API\ProductController;
use App\Models\Food\ProductOrder;
use Carbon\Carbon;

class ProductPriceController extends Controller
{
     public function productPriceSync(){
        
        $data = ProductOrder::whereNotNull('product_code')->where('status', 1)->select('product_code')->get()->toArray();

        if(!empty($data)){
            // push only prpoduct code into array
            $product_code = [];
            foreach ($data as $key => $value) {
                $product_code [] = $value['product_code'];
            }

            // get product price from smart soft
            $product = ProductController::SmartSoftProductPriceArray($product_code);

            if(!empty($product)){

                foreach ($product as $key => $value) {
                    
                    // search product from product_orders table aginst product code
                    $find = ProductOrder::where('product_code', $value->PRODUCT_CODE)->select('id','tp', 'updated_at')->first();

                    // if data match update product_order table
                    if($find){
                        $find->tp           = $value->UNIT_PRICE;
                        $find->updated_at   = Carbon::now();
                        $success            = $find->save();
                    }

                }

                return response()->json(['title'=>'Successfull', 'msg'=> 'Product Price Sync Successfully', 'icon'=>'success'],200); 
                
            }

            

        }

        
    }
}

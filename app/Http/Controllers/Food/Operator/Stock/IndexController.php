<?php

namespace App\Http\Controllers\Food\Operator\Stock;

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
use DB;

class IndexController extends Controller
{
    //index
    public function index(){

        $allQuery = Order::with('product:id,name_en,name_bn,image,type_id,product_order_id,quantity_per_set,price,status','product.sales_product:id,product_code,type,type_bn,category,quantity,status', 'product.type:id,name_en,name_bn,status')
            ->whereHas('order_summary', function ($query) {
                $query->whereNotNull('manager_approve');
                $query->whereNotNull('admin_approve');
            })
            ->where('cv_code', Auth::user()->cv_code)
            ->where('status', 1)
            ->selectRaw('*, sum(quantity) as order_quantity')
            ->groupBy('product_id')
            ->get()
            ->toArray();

        //dd($allQuery);


        $allSale = Sale::where('cv_code', Auth::user()->cv_code)
            ->selectRaw('id,product_id,cv_code,quantity,sales_number,status, sum(quantity) as sum')
            ->groupBy('product_id')
            ->get()
            ->toArray();



        $newData = array();


        if($allQuery){
                
            foreach ($allQuery as $value) {

                foreach ($allSale as $sale) {

                    if($value['product'] && $sale){
                    
                        if($value['product']['id'] == $sale['product_id']){
                            
                            $quantityNew = [ 
                                'sold' => $sale['sum'] ?? null,
                                'order_quantity' => $value['order_quantity'] ?? null
                            ];

                            $value = array_merge($value, $quantityNew);   

                        }

                    }
                    
                }

                if( !empty( $value['product'] ) ){
                    array_push($newData, $value);
                }
                

            }
        }


        // new add 8-20-22

        $finalData = array();
        if($newData){
            foreach ($newData as $key => $value) {
                if($value['product']['quantity_per_set']){

                    $re_use_in_stock = (($value['product']['sales_product']['quantity'] * $value['quantity']) / $value['product']['quantity_per_set']) * $value['order_quantity'];

                    $quantityNew = [
                        
                        'product_name_en' => $value['product']['name_en'] ?? null,
                        'product_name_bn' => $value['product']['name_bn'] ?? null,
                        'product_type_name_en' => $value['product']['type']['name_en'] ?? null,
                        'product_type_name_bn' => $value['product']['type']['name_bn'] ?? null,
                        'product_image' => $value['product']['image'],
                        'in_stock' => $re_use_in_stock ?? 0,
                        'sold' => $value['sold'] ?? 0,
                        'remaining' => $re_use_in_stock - ($value['sold']  ?? 0),
                    ];

                    array_push($finalData, $quantityNew);
                }else{
                    

                    $re_use_in_stock2 = ($value['product']['sales_product']['quantity'] * $value['quantity']);

                    $quantityNew = [
                        
                        'product_name_en' => $value['product']['name_en'] ?? null,
                        'product_name_bn' => $value['product']['name_bn'] ?? null,
                        'product_type_name_en' => $value['product']['type']['name_en'] ?? null,
                        'product_type_name_bn' => $value['product']['type']['name_bn'] ?? null,
                        'product_image' => $value['product']['image'],
                        'in_stock' => $re_use_in_stock2 ?? 0,
                        'sold' => $value['sold'] ?? 0,
                        'remaining' => $re_use_in_stock2 - ($value['sold'] ?? 0),
                    ];

                    array_push($finalData, $quantityNew);
                }
            }
        }

        // new add 8-20-22


        
        return response()->json($finalData, 200);

    }



    public function food_type(){

        $allData = ProductType::get();

        return response()->json($allData, 200);

    }

  

    

    
}

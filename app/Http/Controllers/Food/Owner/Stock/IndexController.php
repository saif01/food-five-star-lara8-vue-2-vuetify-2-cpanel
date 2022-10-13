<?php

namespace App\Http\Controllers\Food\Owner\Stock;

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

class IndexController extends Controller
{
    //index
    public function index(){

        $cv_code      = Request('cv_code', '');

        $allQuery = Order::with('product.sales_product', 'product.type')
            ->whereHas('order_summary', function ($query) {
                $query->whereNotNull('manager_approve');
                $query->whereNotNull('admin_approve');
                // $query->whereDate('order_date', Carbon::today()->toDateString());
            })
            ->where('cv_code', $cv_code)
            ->where('status', 1)
            ->selectRaw('*, sum(quantity) as order_quantity')
            ->groupBy('product_id')
            ->get()
            ->toArray();


        $allSale = Sale::where('cv_code', $cv_code)
            //->whereDate('created_at', Carbon::today()->toDateString())
            ->selectRaw('*, sum(quantity) as sum')
            ->groupBy('product_id')
            ->get()
            ->toArray();


        $newData = array();


        if($allQuery){
                
            foreach ($allQuery as $value) {

                foreach ($allSale as $sale) {

                    if($value['product'] && $sale){

                        if($sale['product_id'] == $value['product']['id']){

                            $quantityNew = array();
                            
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
                        'in_stock' => $re_use_in_stock,
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
                        'in_stock' => $re_use_in_stock2,
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

        $allData = ProductType::get()->toArray();

        return response()->json($allData, 200);

    }

  

    

    
}

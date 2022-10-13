<?php

namespace App\Http\Controllers\Food\Operator\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\ProductSale;
use App\Models\Food\ProductType;
use App\Models\Food\ProductOrderType;
use App\Models\Food\OrderSummary;
use App\Models\Food\Sale;
use Auth;
use Carbon\Carbon;


class IndexController extends Controller
{

    public function index(){

        $search   = Request('search', '');
        $type     = Request('sort_field', '');

        $allQuery = ProductSale::whereNull('deleted_temp')
            ->where('status', 1)
            ->orderBy('type_id')
            ->with([ 'free'=>function($q2){
                $q2->select('id', 'name_en', 'name_bn', 'price', 'price_offer', 'image');
                $q2->whereNull('deleted_temp');
            } ,'type'=>function($q){
                $q->select('id', 'name_en', 'name_bn');
                $q->where('status', 1);
                $q->whereNull('deleted_temp');
            }])
            ->select('id', 'name_en', 'name_bn', 'price', 'price_offer', 'image', 'free_item', 'type_id');
            
                

        if(!empty($type) && $type != 'All'){
            $allQuery->where('type_id', $type );
        }

        $allData =  $allQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) )
        ->orderBy('price_offer', 'desc')
        ->get()
        ->groupBy('type_id')
        ->toArray();

        $allSaleType = ProductType::where('status', 1)->whereNull('deleted_temp')->select('id', 'name_en', 'name_bn')->get()->toArray();
        array_unshift($allSaleType, ['id'=>'All', 'name_en'=>'All', 'name_bn'=>'সব']);

        return response()->json(['allData' =>$allData, 'allSaleType'=>$allSaleType], 200);

    }

    public function sales_type(){
        $allData = ProductType::where('status', 1)->whereNull('deleted_temp')->select('id', 'name_en', 'name_bn')->get()->toArray();
        array_unshift($allData, ['id'=>'All', 'name_en'=>'All', 'name_bn'=>'সব']);
        return response()->json(['allData' =>$allData], 200);
    }

    public function order_type(){
        $allData = ProductOrderType::where('status', 1)->whereNull('deleted_temp')->select('id', 'name_en', 'name_bn')->get()->toArray();
        array_unshift($allData, ['id'=>'All', 'name_en'=>'All', 'name_bn'=>'সব']);
        return response()->json(['allData' =>$allData], 200);
    }
}

<?php

namespace App\Http\Controllers\Food\Operator\Modify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\Sale;
use App\Models\Food\SalesSummury;
use App\Models\Food\ProductSale;
use Carbon\Carbon;
use Auth;

class IndexController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $start_date     = Request('start_date', '');
        $end_date       = Request('end_date', '');

        $allData = SalesSummury::orderBy($sort_field, $sort_direction)
            ->select('id', 'cv_code', 'sales_date', 'sales_numb', 'total_price', 'customer_name', 'customer_number', 'customer_age', 'customer_type')
            ->where('cv_code', Auth::user()->cv_code)
            ->search( trim(preg_replace('/\s+/' ,' ', $search)) )
            ->whereDate('sales_date','>=', $start_date)
            ->whereDate('sales_date','<=', $end_date)
            ->paginate($paginate);


        return response()->json($allData, 200);

    }

    public function order_items(Request $request){
        $data = Sale::with('summury:id,cv_code,sales_date,sales_numb,total_price,customer_name,customer_number,customer_age,customer_type')
        ->select('id','product_id', 'cv_code', 'quantity', 'sales_number', 'free_item', 'status')
        ->where('sales_number', $request->sales_number)
        ->whereNull('free_item')
        ->get();

        $order_product_id = [];
        foreach ($data as $item) {
            array_push($order_product_id, $item->product_id);
        }

        $allData = ProductSale::with('type','free')
            ->whereIn('id', $order_product_id)
            ->get();

        foreach ($allData as $key => $allitem) {
            foreach ($data as $key => $dataitem) {
                if($allitem->id === $dataitem->product_id){
                    $allData[$key]->quantity = $dataitem->quantity ;
                }
            }
        }

        return response()->json(['product'=>$allData, 'sale'=>$data, 200]);
    }


    public function order_details(Request $request){

        $allData = Sale::with('foods:id,name_en,name_bn,type_id,price,price_offer', 'foods.type:id,name_en,name_bn')->where('sales_number', $request->sales_number)
        ->select('id','product_id','cv_code','quantity')
        ->whereNull('free_item')
        ->get();

        return response()->json($allData, 200);
    }


    public function quick_search(){

        $allData = SalesSummury::orderBy('sales_date','desc')
            ->select('id', 'cv_code', 'sales_date', 'sales_numb', 'total_price', 'customer_name', 'customer_number', 'customer_age', 'customer_type')
            ->where('cv_code', Auth::user()->cv_code)
            ->whereDate('sales_date', Carbon::now()->toDateString())
            ->take(10)
            ->get()
            ->toArray();

        return response()->json($allData, 200);
    }


}

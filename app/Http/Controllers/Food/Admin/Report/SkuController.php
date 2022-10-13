<?php

namespace App\Http\Controllers\Food\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\Order;
use App\Models\Food\OrderSummury;
use App\Models\Food\ProductOrder;
use App\Models\Food\Zone;
use App\Models\Food\Outlet;


use App\Exports\SkuExport;
use Maatwebsite\Excel\Facades\Excel;

class SkuController extends Controller
{


    public function index(){

        $allData = $this->getData();
        return response()->json($allData, 200);
    }



    public function getData(){

        $zone         = Request('zone', '');
        $start_date   = Request('start_date', '');

        // get all cv code in a specific zone
        $allCvCodeinZone = Outlet::select('cv_code')->where('zone_id', $zone)->get();
        
        $currentCvCodeArray = [];
        foreach ($allCvCodeinZone as $item) {
            array_push($currentCvCodeArray, $item->cv_code );
        }


        $orderAllQuery = Order::with('product_order_table')
            ->whereHas('order_summary', function ($query) use($start_date) {
                $query->where('order_date', $start_date);
            });

            if($zone != 'All'){
                $orderAllQuery->whereIn('cv_code', $currentCvCodeArray);
            }
        
        $allOrder = $orderAllQuery->selectRaw('*, sum(quantity) as quantity')->groupBy('product_id')->orderBy('quantity', 'desc')->get();


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


        $allData = [
           'allData' => $newData,
           'totalWeight' => $totalWeight,
           'totalPrice' => $totalPrice,
           'totalQuantity' => $totalQuantity,
        ];
        

        return $allData;

    }



    public function export_data_sku(){

        $allData = $this->getData();

        if(Request('zone') != 'All'){
            $zone = Zone::where('id', Request('zone'))->select('name')->first();
        }

        $allData +=  [
            'zoneName' => $zone->name ?? 'All',
            'date'  => '('. date('d F, Y', strtotime(Request('start_date'))).') ',
        ];


        
        return Excel::download(new SkuExport( (object) $allData), 'product-' . time() . '.xlsx');
    }
    
}

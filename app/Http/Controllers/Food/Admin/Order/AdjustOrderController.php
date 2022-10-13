<?php

namespace App\Http\Controllers\Food\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\OrderSummary;
use App\Models\Food\ProductOrder;
use App\Models\Food\Order;
use App\Models\Food\Outlet;

class AdjustOrderController extends Controller
{
    //index
    public function index(){

        $date     = Request('order_date');
        $product  = Request('product');
        $zone     = Request('zone', '');

        // get all cv code in a specific zone
        $allCvCodeinZone = Outlet::select('cv_code')->where('zone_id', $zone)->get();
        
        $currentCvCodeArray = [];
        foreach ($allCvCodeinZone as $item) {
            array_push($currentCvCodeArray, $item->cv_code );
        }


        $allQuery = Order::with('order_summary', 'order_summary.outlet_details')
        ->whereHas('order_summary', function ($query) use($date) {
            $query->where('order_date', $date);
            // $query->whereNull('manager_approve');
            $query->whereNull('admin_approve');
        });

        if($zone != 'All'){
            $allQuery->whereIn('cv_code', $currentCvCodeArray);
        }
        $data = $allQuery
            ->where('product_id', $product)
            ->get()
            ->toArray();





        $allData = ProductOrder::orderBy("id", "asc")
            ->whereNull('deleted_temp')
            ->where('status', '1')
            ->select('id', 'type', 'category', 'quantity', 'tp', 'weight', 'weight_type','status')
            ->get()
            ->toArray();

        $newData = [];

        if($data){
            foreach ($allData as  $value) {

                foreach ($data as $value2) {
                    
                    if($value2['product_id'] === $value['id']){

                        $opt = [
                            'quantity_order' => $value2['quantity'],
                            'outlet_name' => $value2['order_summary']['outlet_details']['shop_name'],
                            'cv_code'=> $value2['order_summary']['outlet_details']['cv_code'],
                            'order_number'=>$value2['order_summary']['order_number'],
                            'order_id'=>$value2['id'],
                            'order_summury_id'=>$value2['order_summary']['id'],
                        ];
                        
                        $value = array_merge($value, $opt);

                        array_push($newData, $value);
                    }
                }
                
            }

        }
        else{
            $newData = null;
        }


        return response()->json($newData, 200);
    }



    public function modify(Request $request){
        
        $items       = $request->items;
        $order_date  = $request->order_date;

        //dd($items);


        foreach ($items as $key => $value) {
            
            // modify order table order
            $order = Order::find($value['order_id']);

            $order->quantity     = $value['quantity_order'];
            $success             = $order->save();

            // get total price of order

            if($success){
                $order_total_price = Order::where('order_number', $value['order_number'])->get()->toArray();
                $totalPrice = 0;
                foreach ($order_total_price as $key => $total) {
                    $totalPrice       += $total['price'] * $total['quantity'];
                }
            }



            // modify order summury table order

            $orderSummury = OrderSummary::find($value['order_summury_id']);

            $orderSummury->total_price     = $totalPrice;
            $success                       = $orderSummury->save();

        }

        if($success){
                $allData = [
                    'status'    => 'success',
                    'msg'       => 'Order Successfullt Adjusted',
                    'data'      => '',
                ];
                
            }else{
                $allData = [
                    'status'    => 'error',
                    'msg'       => 'Something went wrong. please try again',
                    'data'      => '',
                ];

            }

            return response()->json($allData);
        

    }

}

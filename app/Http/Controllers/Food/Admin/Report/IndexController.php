<?php

namespace App\Http\Controllers\Food\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Food\Sale;
use App\Models\Food\SalesSummury;
use App\Models\Food\Zone;
use App\Models\Food\Outlet;
use DB;

class IndexController extends Controller
{
    //outlet report
    public function index(){

        $cv_code      = Request('cv_code', '');
        $start_date   = Request('start_date', '');
        $end_date     = Request('end_date', '');

        $from = $start_date . ' 00:00:00';
        $to = $end_date . ' 00:00:00' ;

        $allSale = Sale::with('foods')->where('cv_code', $cv_code)
        ->whereDate('created_at','>=', $from)
        ->whereDate('created_at','<=', $to)
        ->selectRaw('*, sum(quantity) as sum')
        ->groupBy('product_id')
        ->get();


        $allSaleAmount = SalesSummury::where('cv_code', $cv_code)
        ->selectRaw('cv_code, sum(total_price) as total_sale')
        ->groupBy('cv_code')
        ->first();


        //dd($allSale);

        return response()->json(['allSale'=>$allSale, 'allSaleAmount'=>$allSaleAmount], 200);
    }

    // report for every product
    // public function every_product(){
    //     $food_item    = Request('food_item', '');
    //     $start_date   = Request('start_date', '');
    //     $end_date     = Request('end_date', '');

    //     $from = $start_date . ' 00:00:00';
    //     $to = $end_date . ' 00:00:00' ;

    //     $allData = Sale::with('foods')->where('product_id', $food_item)
    //     ->whereDate('created_at','>=', $from)
    //     ->whereDate('created_at','<=', $to)
    //     ->selectRaw('*, sum(quantity) as sum')
    //     ->selectRaw('DATE(created_at) as date')
    //     ->groupBy('date')
    //     ->get();


    //     // dd($allData);

    //     return response()->json($allData, 200);
    // }


    // public function get_zone(){

    //     $allData = Zone::get();
    //     return response()->json($allData, 200);
    // }

    // // zone wise sale report
    // public function zone_report(){

    //     $zone      = Request('zone', '');
    //     $start_date   = Request('start_date', '');
    //     $end_date     = Request('end_date', '');

    //     $from = $start_date . ' 00:00:00';
    //     $to = $end_date . ' 00:00:00' ;

    //     // get all cv code in a specific zone
    //     $allCvCodeinZone = Outlet::select('cv_code')->where('zone_id', $zone)->get();
        
    //     $currentCvCodeArray = [];
    //     foreach ($allCvCodeinZone as $item) {
    //         array_push($currentCvCodeArray, $item->cv_code );
    //     }


    //     $allData = Sale::with('foods')->whereIn('cv_code', $currentCvCodeArray)
    //         ->whereDate('created_at','>=', $from)
    //         ->whereDate('created_at','<=', $to)
    //         ->selectRaw('*, sum(quantity) as sum')
    //         ->groupBy('product_id')
    //         ->get();


    //     // dd($allData);

    //     return response()->json($allData, 200);
    // }

     public function hourly_report(){

        $zone      = Request('zone', '');
        $by_date      = '2022-07-24';


        // get all cv code in a specific zone
        $allCvCodeinZone = Outlet::select('cv_code')->where('zone_id', $zone)->get();
        
        $currentCvCodeArray = [];
        foreach ($allCvCodeinZone as $item) {
            array_push($currentCvCodeArray, $item->cv_code );
        }

        $data = Sale::with('foods.type');
    
        if( $zone && $zone != "All" ){
            $data->whereIn('cv_code', $currentCvCodeArray) ;
        }
        //$allData = $data->whereDate('created_at', Carbon::today())
        $allData = $data->whereDate('created_at', $by_date )
        //->select("*", DB::raw("SUM(quantity) AS total"))
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->addHour(6)->format('h A');
        })
        ->toarray();


        $newData = [];

        foreach ($allData as $key => $value) {
            array_push($newData,[$key=>self::group_by('product_id', $value)]);
        }

        //dd($newData);

        // $finalOutput = [];
        // foreach ($newData as $key => $value2) {
        //     foreach($value2 as $key =>$val3){

        //         $salesTime = ['sales_time'=> $key];

        //         //dd($key, $value2);
        //         foreach($val3 as $key =>$val4){
        //             $total = 0;
        //             foreach($val4 as $val5){
        //                  $total += $val5['quantity'];
        //             }
        //             //dd($total, $val4);
        //             $totalItem  = ['total_quantity'=>$total];
        //             $totalval  = ['total_product'=>count($val4)];
        //             $mrgeArr   = array_merge($val4[0], $totalval, $totalItem, $salesTime);
        //             array_push($finalOutput, $mrgeArr);
        //         }
        //     }
        // }

        // $finalOutput2 = self::group_by('sales_time', $finalOutput);

        
        //dd($newData);
            
        $allChartData = self::chartData($newData);

        // $chartValData = [];
        // foreach( $allChartData['chart_value'] as $item_val ){
        //    // dd($item_val);
        //     //$chartValData [] = ['data' => $item_val];

        //     array_push($chartValData, ['data' => $item_val]);
        // }



        // dd( $chartValData,  $allChartData);

        return response()->json(['finalOutput'=>$newData, 'allChartData'=>$allChartData], 200);
    }



    public function group_by($key2, $data) {
        // $result = array();

        // foreach($data as $val) {
        //     if(array_key_exists($key, $val)){
        //         $result[$val[$key]][] = $val;
        //     }else{
        //         $result[""][] = $val;
        //     }
        // }
        // return $result;


        $groups = array();
        foreach ($data as $item) {

            $key = $item[$key2];
            if (!array_key_exists($key, $groups)) {

                $groups[$key] = array(
                    'id'  => $item['id'],
                    'cv_code' => $item['cv_code'],
                    'updated_at'  => $item['updated_at'],
                    'quantity' => $item['quantity'],
                    'foods_name' => $item['foods']['name_en'],
                    'foods_image' => $item['foods']['image'],
                    'type_name' => $item['foods']['type']['name_en'],
                );
            } else {
                $groups[$key]['quantity'] = $groups[$key]['quantity'] + $item['quantity'];
            }
        }
        return $groups;
    }


    public function chartData($data){

        $chart_bottom_labels = [];
        $data_label_name = [];
        $chart_value = [];


        $max_length = [];

        $firstArray = [];


        foreach ($data as $key => $value) {

            foreach ($value as $key => $items) {

                array_push($chart_bottom_labels, $key);

                array_push( $chart_value,array_column($items, 'quantity'));

                array_push( $max_length, count($items));
                
            }
            
        }

        for ($i=0; $i < max($max_length) ; $i++) {

            foreach ($chart_value as $key => $value) {

                array_push($firstArray, $value[$i] ?? '' );        
            }

        }
        

        $final_value = array_chunk($firstArray, max($max_length)-1,true);

       
        $chartData = [
           'bottom_label' => $chart_bottom_labels,
           'data_label'   => $data_label_name,
           'chart_value'  => $final_value,
        ];

        return $chartData;

    }
}

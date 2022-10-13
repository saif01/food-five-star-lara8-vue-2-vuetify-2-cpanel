<?php

namespace App\Http\Controllers\Food\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Food\Outlet;
use App\Models\Food\SalesSummury;
use App\Models\Food\OrderSummary;
use Auth;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\CarbonPeriod;

class IndexController extends Controller
{
    public function Index(){

        $roles = Auth::user()->roles->pluck('slug');

        return view('food.admin.index', compact('roles'));
    }


    // dashboard data
    public function dashboard_data(){

        $outlet = Outlet::count();
        $operator = User::where('user_type', 'op')->count();
        $owner = User::where('user_type', 'wn')->count();

        $salesSummaryQuery = SalesSummury::query()->with('outlet_details:id,cv_code,shop_name,zone_id','outlet_details.zone:id,name');

        $top_five_shop_sale = (clone $salesSummaryQuery)
        // $top_five_shop_sale = SalesSummury::with('outlet_details','outlet_details.zone')
        ->whereDate('sales_date', Carbon::today())
        ->selectRaw('cv_code, sum(total_price) as today_sale')
        ->orderBy('today_sale','DESC') 
        ->take(5)   
        ->groupBy('cv_code')
        ->get();

        // dd($top_five_shop_sale);

        if( $top_five_shop_sale->isEmpty() ){
            $top_five_shop_sale = null;
        }else{
            $chlabels_top5 = [];
            $chdata_top5 = [];
            foreach ($top_five_shop_sale as $key => $value) {
                //dd($value);
                $chlabels_top5[] = $value->outlet_details->cv_code . ' - ' .  $value->outlet_details->shop_name  .' ('. $value->outlet_details->zone->name .')';
                $chdata_top5[]   = $value->today_sale;
            }
        }

        // $last_seven_days_sale = SalesSummury::with('outlet_details','outlet_details.zone')
        $last_seven_days_sale = (clone $salesSummaryQuery)
        ->whereDate('sales_date', '<=' , Carbon::today())
        ->whereDate('sales_date', '>=' , Carbon::today()->subday(7))
        ->selectRaw('cv_code, sum(total_price) as today_sale')
        ->orderBy('today_sale','DESC') 
        ->take(20)   
        ->groupBy('cv_code')
        ->get();

        if( $last_seven_days_sale->isEmpty() ){
            $last_seven_days_sale = null;

        }else{
            $chlabels_7days = [];
            $chdata_7days = [];
            foreach ($last_seven_days_sale as $key => $value) {
                $chlabels_7days[] = $value->outlet_details->cv_code . ' - ' . $value->outlet_details->shop_name .' ('. $value->outlet_details->zone->name .')';
                $chdata_7days[]   = $value->today_sale;
            }
        }

        

        // $start = '2022-07-30';
        // $end   = '2022-08-30';
        $end = Carbon::now()->format('Y-m-d');
        $start   = Carbon::now()->subDays(30)->format('Y-m-d');

        // dd( $start,  $end);

        $period = CarbonPeriod::create($start,$end);
        $line_chart_lavel = [];
        $dbDate = [];
        // Iterate over the period
        foreach ($period as $date) {
            // $line_chart_lavel[]= $date->format('jS-M-y');
            $line_chart_lavel[]= $date->format('j-M-y');
            $dbDate[]= $date->format('Y-m-d');
        }

        $sales_sum = [];
        $orders_sum = [];
        if($dbDate){
            foreach($dbDate as $item){
                $sales_sum []  = round(SalesSummury::whereDate('sales_date', $item)->sum('total_price'), 2);
                $orders_sum [] = round(OrderSummary::whereDate('order_date', $item)->sum('total_price'), 2);
            }
        }

        //dd($sales_sum, $orders_sum);

        $line_chart_data = [
            [
                'data' => $sales_sum,
                'label' => 'Sales',
                'borderColor'=> "#F62F63",
                'fill' => false,
            ],
            [
                'data' => $orders_sum,
                'label' => 'Order',
                'borderColor'=> "#41CBBF",
                'fill' => false,
            ],

        ];

        //dd($sale_order_chart_data);


       // dd( $sale_order_chart_data, $line_chart_lavel );

       $allData = [
            'outlet'=>$outlet ?? null, 
            'operator'=>$operator ?? null, 
            'owner'=>$owner ?? null, 
            'top_five_shop_sale'=>$top_five_shop_sale ?? null, 
            'last_seven_days_sale'=> $last_seven_days_sale ?? null,

            'line_chart_lavel'    => $line_chart_lavel ?? null,
            'line_chart_data'    => $line_chart_data ?? null,

            'bar_chart_label'   => $chlabels_7days ?? null,
            'bar_chart_data'   => $chdata_7days ?? null,

            'doughnut_chart_label'   => $chlabels_top5 ?? null,
            'doughnut_chart_data'   => $chdata_top5 ?? null,
       ];

        return response()->json(['allData' => $allData],200);
    }


    public static function GetAllDateBetweentTwoDate($start, $end, $format = 'Y-m-d'){
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        // dd($end, $realEnd);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }

        return $array;
    }

}

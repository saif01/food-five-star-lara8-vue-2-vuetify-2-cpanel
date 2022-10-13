<?php

namespace App\Http\Controllers\Food\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Food\Announcement;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Food\SalesSummury;
use App\Models\Food\Outlet;

class IndexController extends Controller
{
    public function Index(){

        return view('food.owner.index');
    }

    // get_announcement
    public function get_announcement()
    {
        $date = Carbon::today()->toDateString();
        $allData = Announcement::whereIn('allow', ['owner', 'all'])
        ->where('status', 1)
        ->whereDate('start','<=', $date)
        ->whereDate('end','>=', $date)
        ->select('image')
        ->get()
        ->toArray();
        return response()->json($allData, 200);
    }

    // dashboard_data
    public function dashboard_data(){

        //dd('ok', Request()->all());
        

        $start_date   = Request('start_date', '');
        $end_date     = Request('end_date', '');
        $cv_code      = Request('cv_code', '');

        $month        = Request('month', ''). '-01';
        $year         = Request('year', '');

        $from = $start_date . ' 00:00:00';
        $to = $end_date . ' 00:00:00' ;


        $shop_info = Outlet::where('cv_code', $cv_code)->select('shop_name', 'shop_address')->first();

        $all_query = SalesSummury::where('cv_code', $cv_code);

        if( Request('req') == 'm'){
            $startMonth = Carbon::parse($month)->startOfMonth()->toDateString();
            $endMonth = Carbon::parse($month)->endOfMonth()->toDateString();

            $all_query->whereDate('sales_date','>=', $startMonth)
            ->whereDate('sales_date','<=', $endMonth)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total_price) as total_sale'));
            
        }else if(Request('req') == 'y'){

            $date = Carbon::createFromDate($year);

            $startYear = $date->copy()->startOfYear()->toDateString();
            $endYear   = $date->copy()->endOfYear()->toDateString();


            $all_query->whereDate('sales_date','>=', $startYear)
            ->whereDate('sales_date','<=', $endYear)
            ->select(DB::raw('MONTHNAME(created_at) as date'), DB::raw('sum(total_price) as total_sale'));

        }else{
            $all_query->whereDate('sales_date','>=', $from)
            ->whereDate('sales_date','<=', $to)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total_price) as total_sale'));


        }
        
        $all_sale = $all_query->groupBy('date')
        ->orderBy('total_sale', 'desc')
        ->get();


        $labels = [];
        $data = [];
        if($all_sale){
            foreach($all_sale as $value){
                if(Request('req') == 'y'){
                    $labels[] = Carbon::parse($value->date)->format('M Y') ?? null;
                }else{
                    $labels[] = Carbon::parse($value->date)->format('jS M Y') ?? null;
                }
                
                $data[] = $value->total_sale ?? null;
            }
        }

        

        $chart_data = ['labels' => $labels, 'data' => $data, 'total' => array_sum($data)];
        // dd($chart_data);
        return response()->json(['chart_data' => $chart_data, 'day_wise_sale' => $all_sale, 'shop_info'=>$shop_info],200);

    }



    // Manual Group By Key with Sum
    // public static function ManualGroupByKeySum($data, $key_name='date') {
    //     $groups = [];
    //     $details = [];
    //     // $groups = array();
    //     foreach ($data as $item) {
    //         $item = (array) $item;
    //         $key = $item[$key_name];
    //         if (!array_key_exists($key, $groups)) {
    //             $groups[$key] = array(
    //                 'sales_date' => $item['date'],
    //                 'total'      => $item['total_price'],
    //                 'outlet'     => $item['outlet_details']['shop_name'],
    //             );
    //         } else {
    //             $groups[$key]['total'] = $groups[$key]['total'] + $item['total_price'];
    //         }
    //     }
    //     // dd( $groups);
    //     return $groups;
    // }
}

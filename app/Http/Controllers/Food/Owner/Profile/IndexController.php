<?php

namespace App\Http\Controllers\Food\Owner\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\SalesSummury;
use App\Models\Food\Sale;
use App\Models\User;
use App\Models\Food\Outlet;
use Auth;
use Carbon\Carbon;
use DB;

class IndexController extends Controller
{


    public function index(){

        $cv_code = Request('cv_code', '');

        $data = Outlet::with('zone', 'manager', 'officer')->where('status', 1)->where('cv_code', $cv_code)->first();

        return response()->json($data, 200);
    }


    public function shop(){
        $allData = User::find(Auth::user()->id);
        if($allData->cv_code_owner)
        {  
            $cvCodeArr = explode(",", $allData->cv_code_owner);
            // get all outlet against code 
            $allData = Outlet::with('user:cv_code,last_activity')->where('status', 1)->whereIn('cv_code', $cvCodeArr)->select('shop_name', 'cv_code', 'shop_address')->get();
        }else{
            $allData = null;
        }

         // Append Filed Data
        foreach($allData as $item){
        
            $online = false;

            $lastActivity = $item->user->last_activity ?? null;

            if($lastActivity){
                $nowTime = Carbon::now();
                if( $nowTime->lessThanOrEqualTo($lastActivity) ){
                    $online = true;
                }
            }

            $allSaleAmount = SalesSummury::where('cv_code', $item->cv_code)
            ->select("cv_code", DB::raw("(sum(total_price)) as total_sale"),
                    DB::raw("(DATE_FORMAT(sales_date, '%d-%m-%Y')) as my_date"))
            ->orderBy('sales_date', 'desc')
            ->groupBy(DB::raw("DATE_FORMAT(sales_date, '%d-%m-%Y')"))
            ->take(7)
            ->get();

            if(!empty($allSaleAmount)){
                $chlabels = [];
                $chdata = [];
                foreach ($allSaleAmount as $key => $value) {
                    $chlabels[] = $value->my_date;
                    $chdata[]   = $value->total_sale;
                }
            }

            
            
            $item->online =  $online;
            // $item->sale =  $allSaleAmount ?? null;
            $item->chLabels =  $chlabels ?? null;
            $item->chData =  $chdata ?? null;
        }

        return response()->json($allData, 200);

    }
        
   
}

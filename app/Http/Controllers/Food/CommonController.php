<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\Outlet;
use App\Models\Food\OrderSummary;
use Illuminate\Support\Str;
use App\Models\Food\Zone;
use App\Models\User;
use Auth;
use DB;
use Artisan; 
use Illuminate\Contracts\Foundation\Application;
use Gate;
use Storage;
use App\Models\Food\ScheduleTable;

class CommonController extends Controller
{

    // public $MaintanceMood = false;

    // public function __construct(Application $app)
    // {
    //     $this->MaintanceMood = $app->isDownForMaintenance();
    // }

    //All Outlets
    public function all_outlets(){
        $allData = Outlet::whereNull('deleted_temp')->where('status', 1)
        ->where('status', 1)
        ->orderBy('cv_code')
        ->get();

        return response()->json($allData, 200);
    }

    



    //RunningNumberGenerate
    public static function RunningNumberGenerate( $prefix=null ){

        if($prefix){
           $runningNumber =  $prefix.date("Ymd", strtotime("now")).random_int(1000, 9999);
        }else{
            $runningNumber =  date("Ymdhm", strtotime("now")).random_int(1000, 9999);
        }
        // with CV 6 digit + 8 digit + 4
        return $runningNumber;
    }

    // sales running number
    public static function salesRunningNumber($cv=null, $runningNum=null){

        if(empty($cv)){ return 'CV Code Empty'; }
        if( !empty($runningNum) ){
            // $runningNum = (int) $runningNum;
            $runningNumber = (int) substr( $runningNum,14 ) + 1;
            // Get 8 digit number
            $fiveDigitSeqNumber = sprintf("%'.06d", $runningNumber);
            // 6 + 8 + 6 total 20 digit code
            return $cv.date("Ymd", strtotime("now")).$fiveDigitSeqNumber;
        }else{
            return $cv.date("Ymd", strtotime("now")).'000001';
        }

    }



    public function test(){

        $cv = '182189';
        $salesNum = '18218920220625002005';

        return self::salesRunningNumber($cv, $salesNum);

    }

    // all_zones
    public function all_zones(){

        $zons = Zone::query()->whereNull('deleted_temp')->where('status', '1')->select('id', 'name');
        $zoneData = (clone $zons)->orderBy('name')->get();
        // Custom Field Data Add
        $custom = collect( [['id' => 'All', 'name' => 'All']] );
        $zoneShortData = $custom->merge($zoneData);
        return response()->json(['zoneData'=>$zoneData,'zoneShortData'=>$zoneShortData], 200);
    }


    // all_assigned_zones_cvcode
    public function all_assigned_zones_cvcode(){
        $data =  self::GetZoneCVcodeAssignZone();
        // Assending order
        asort($data);
        // Custom Field Data Add
        $custom = [['id' => 'All', 'name' => 'All',  'cv_code'=> 'All' ]];
        $zoneShortData = array_merge($custom, $data);
        return response()->json($zoneShortData, 200);
    }

    // all_assigned_outlets_byzone
    public function all_assigned_outlets_byzone(){

        $cv_code_arr = self::GetCVcodeByAssignedZone();
        
        $data = Outlet::whereIn('cv_code', $cv_code_arr)
        ->whereNull('deleted_temp')
        ->where('status', 1)
        ->select( 'id', 'cv_code', 'shop_name', 'shop_address', DB::raw("CONCAT_WS(' || ', cv_code, shop_name, shop_address) AS text, cv_code AS value"))
        ->orderBy('cv_code')
        ->get();
        //->toArray();
        //dd($data);
        return response()->json($data, 200);

    }







    // Get Zone with CVcode Assign by Zone
    public static function GetZoneCVcodeAssignZone(){

        $users = Auth::user()->zones()->get()->toArray();
        $assignedZone = [];
        foreach ($users as $value) {

            $zone_ID = $value['id'];
            // Get Assigned CV Code By Zone ID
            $cv_code_arr = Outlet::where('zone_id', $zone_ID)->pluck('cv_code')->toArray();
            $assignedZone [] = [
                'id' => $zone_ID,
                'name' => $value['name'],
                'cv_code' => $cv_code_arr
            ];
        }
        return $assignedZone;
    }


    // Get only CVcode Assigned by Zone
    public static function GetCVcodeByAssignedZone(){

        $users = Auth::user()->zones()->get()->toArray();
        $assignedCVcode = [];
        foreach ($users as $value) {
            // Get Assigned CV Code By Zone ID
            $CVcodeArr = Outlet::where('zone_id', $value['id'])->pluck('cv_code')->toArray();
            foreach($CVcodeArr as $val3){
                array_push($assignedCVcode,  $val3);
            }
        }
        return $assignedCVcode;
    }


    // Get only Zones Assigned by Zone
    public static function GetZonesByAssignedZone(){
        $users = Auth::user()->zones()->get()->toArray();
        $assignedZone = [];
        foreach ($users as $value) {
            $assignedZone [] = [
                'id' => $value['id'],
                'name' => $value['name'],
            ];
        }
        return $assignedZone;
    }

    public static function checkSameDaySameShopOrder($date, $cv_code){

        $data = OrderSummary::where('cv_code', $cv_code)->where('order_date', $date)->first();

        if($data){
            return true;
        }else{
            return false;
        }

    }

    public static function checkModifyOrderSameDaySameShop($order_number, $cv_code, $order_date){
        
        $check = OrderSummary::where('order_number', $order_number)->first();

        if($check){
            if($check->cv_code == $cv_code && $check->order_date == $order_date){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
        
        
    }




    // app_maintance_mode
    public function app_maintance_mode(){

        if(Request('status') == 'cs'){
            // Check Access
            if( Gate::allows('administration') ){

                if (self::IsMaintenance()) {
                    Artisan::call('up');
                    $maintenance = false;
                }else{
                    Artisan::call('down');
                    $maintenance = true;
                }
            }
        }
        else{
            if (self::IsMaintenance()) {
                $maintenance = true;
            }else{
                $maintenance = false;
            }
        }

        // 5dd($maintenance);
        return response()->json($maintenance ,200);
    }

    // Check Maintance Mood On
    public static function IsMaintenance(){
        return Storage::disk('framework')->exists('/down');
    }


}

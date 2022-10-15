<?php

namespace App\Http\Controllers\Food\Owner\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\SalesSummury;
use App\Models\Food\Sale;
use App\Models\User;
use App\Models\Food\Outlet;
use Auth;
use Carbon\Carbon;
use App\Exports\OwnerExport;
use Maatwebsite\Excel\Facades\Excel;


class IndexController extends Controller
{

    public function index(){

        $allData = $this->getData();
        if (count($allData['allData']) != '0') {
            $chlabels = [];
            $chdata = [];

            foreach ($allData['allData'] as $key => $item) {
                
                if ($item['foods']) {
                    array_push($chlabels, $item['foods']['name_en']);
                }
                array_push($chdata, $item['sum']);

            };

            $dataArry = [
                'labels'=> $chlabels,
                'data'=> $chdata,
            ];

        }else{
            $dataArry = null;
        }

        return response()->json(['allData'=>$allData, 'dataArry'=>$dataArry, 200]);
    }


    public function getData(){
        

        $start_date   = Request('start_date', '');
        $end_date     = Request('end_date', '');
        $cv_code      = Request('cv_code', '');
        

        $from = $start_date . ' 00:00:00';
        $to = $end_date . ' 00:00:00' ;


        $allData = Sale::where('cv_code', $cv_code)
        ->whereDate('created_at','>=', $from)
        ->whereDate('created_at','<=', $to)
        ->with([ 'foods'=>function($q2){
                $q2->select('id','name_en', 'name_bn', 'image', 'type_id');
            } ,'foods.type'=>function($q){
                $q->select('id','name_en', 'name_bn');
            }])
        ->whereNull('free_item')
        ->selectRaw('*, sum(quantity) as sum')
        ->groupBy('product_id')
        ->orderBy('sum', 'desc')
        ->get()
        ->toArray();

        $allSaleAmount = SalesSummury::where('cv_code', $cv_code)
        ->whereDate('sales_date','>=', $from)
        ->whereDate('sales_date','<=', $to)
        ->selectRaw('cv_code, sum(total_price) as total_sale')
        ->groupBy('cv_code')
        ->first();

        $allData = [
           'allData' => $allData,
           'allSaleAmount' => $allSaleAmount,
        ];
        

        return $allData;
    }


    public function get_all_shop(){
        $allData = User::find(Auth::user()->id);
        if($allData->cv_code_owner)
        {  
            $cvCodeArr = explode(",", $allData->cv_code_owner);
            // get all outlet against code 
            $allData = Outlet::with('user:cv_code,last_activity')
            ->where('status', 1)
            ->whereIn('cv_code', $cvCodeArr)
            ->select('shop_name', 'cv_code', 'shop_address', 'latitude', 'longitude')
            ->get();


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
                
                $item->online =  $online;
            }


        }else{
            $allData = null;
        }

        
        return response()->json($allData, 200);

    }



    public function export_data(){

        $allData = $this->getData();

        $data = Outlet::where('cv_code', Request('cv_code'))->select('shop_name', 'cv_code', 'shop_address')->first();
        
        $allData +=  [
            'date'  => '('. date('d F, Y', strtotime(Request('start_date'))) .' to '.date('d F, Y', strtotime(Request('end_date'))) .')',
            'shop'  => $data,
        ];
        
        return Excel::download(new OwnerExport( (object) $allData), 'product-' . time() . '.xlsx');

    }

    public function shop(){
        $data = Outlet::where('cv_code', Request('cv_code'))->select('shop_name', 'cv_code', 'shop_address')->first();
        return response()->json($data,200);
    }
        
   
}

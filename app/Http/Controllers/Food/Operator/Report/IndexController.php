<?php

namespace App\Http\Controllers\Food\Operator\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\SalesSummury;
use App\Models\Food\Sale;
use App\Models\Food\Outlet;
use Auth;
use Carbon\Carbon;

use App\Exports\OperatorExport;
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
        

        $from = $start_date . ' 00:00:00';
        $to = $end_date . ' 00:00:00' ;


        $allData = Sale::with([ 'foods'=>function($q2){
                                $q2->select('id','name_en', 'name_bn', 'image', 'type_id');
                            } ,'foods.type'=>function($q){
                                $q->select('id','name_en', 'name_bn');
                            }])
        ->where('cv_code', Auth::user()->cv_code)
        ->whereNull('free_item')
        ->whereDate('created_at','>=', $from)
        ->whereDate('created_at','<=', $to)
        ->selectRaw('*, sum(quantity) as sum')
        ->groupBy('product_id')
        ->orderBy('sum', 'desc')
        ->get()
        ->toArray();

        //dd($allData);

        $allSaleAmount = SalesSummury::where('cv_code', Auth::user()->cv_code)
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

    public function export_data(){

        $allData = $this->getData();
        $data = Outlet::where('cv_code', Auth::user()->cv_code)->select('shop_name', 'cv_code', 'shop_address')->first();

        
        $allData +=  [
            'date'  => '('. date('d F, Y', strtotime(Request('start_date'))) .' to '.date('d F, Y', strtotime(Request('end_date'))) .')',
            'shop'  => $data,
        ];

        
        return Excel::download(new OperatorExport( (object) $allData), 'product-' . time() . '.xlsx');

    }
        
   
}

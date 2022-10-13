<?php

namespace App\Http\Controllers\Food\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Food\Sale;
use App\Models\Food\SalesSummury;
use App\Models\Food\Zone;
use App\Models\Food\Outlet;

use App\Exports\OutletExport;
use Maatwebsite\Excel\Facades\Excel;

class OutletController extends Controller
{
    //outlet report

    public function index(){

        $allData = $this->getData();

        if (count($allData['allSale']) != '0') {
            $chlabels = [];
            $chdata = [];

            foreach ($allData['allSale'] as $key => $item) {
                
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

        $cv_code      = Request('cv_code', '');
        $start_date   = Request('start_date', '');
        $end_date     = Request('end_date', '');

        $allSale = Sale::with('foods.type')->where('cv_code', $cv_code)
        ->whereNull('free_item')
        ->whereDate('created_at','>=', $start_date)
        ->whereDate('created_at','<=', $end_date)
        ->selectRaw('*, sum(quantity) as sum')
        ->groupBy('product_id')
        ->orderBy('sum', 'desc')
        ->get()
        ->toArray();


        $allSaleAmount = SalesSummury::where('cv_code', $cv_code)
        ->whereDate('sales_date','>=', $start_date)
        ->whereDate('sales_date','<=', $end_date)
        ->selectRaw('cv_code, sum(total_price) as total_sale')
        ->groupBy('cv_code')
        ->first();

        $allData = [
           'allSale' => $allSale,
           'allSaleAmount' => $allSaleAmount,
        ];
        

        return $allData;
    }




    public function export_data_outlet(){

        $allData = $this->getData();

        $outlet = Outlet::where('cv_code', Request('cv_code'))->select('shop_name')->first();


        $allData +=  [
            'outletName'  => $outlet->shop_name,
            'date'  => '('. date('d F, Y', strtotime(Request('start_date'))) .' to '.date('d F, Y', strtotime(Request('end_date'))) .') ',

        ];

        return Excel::download(new OutletExport( (object) $allData), 'product-' . time() . '.xlsx');
    }
}

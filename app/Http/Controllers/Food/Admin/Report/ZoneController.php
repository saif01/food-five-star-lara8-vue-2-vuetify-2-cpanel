<?php

namespace App\Http\Controllers\Food\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\Outlet;
use App\Models\Food\Sale;
use App\Models\Food\Zone;

use App\Exports\ZoneExport;
use Maatwebsite\Excel\Facades\Excel;

class ZoneController extends Controller
{

    //index
    public function index(){
        $allData = $this->getData();

        // For chart
        if (count($allData) > 0) {
            $chlabels = [];
            $chdata = [];

            foreach ($allData as $key => $item) {

                array_push($chlabels, $item['foods']['name_en']);
                array_push($chdata, $item['sum']);
                
            };

            
        }

        $dataArry = [
                'labels'=> $chlabels ?? null,
                'data'=> $chdata ?? null,
            ];

        return response()->json(['allData'=>$allData, 'dataArry'=>$dataArry, 200]);
    }

    public function getData(){

        $zone         = Request('zone', '');
        $start_date   = Request('start_date', '');
        $end_date     = Request('end_date', '');

        $from = $start_date . ' 00:00:00';
        $to = $end_date . ' 00:00:00' ;

        // get all cv code in a specific zone
        $allCvCodeinZone = Outlet::select('cv_code')->where('zone_id', $zone)->get();
        
        $currentCvCodeArray = [];
        foreach ($allCvCodeinZone as $item) {
            array_push($currentCvCodeArray, $item->cv_code );
        }


        $allData = Sale::with('foods.type')->whereIn('cv_code', $currentCvCodeArray)
            ->whereNull('free_item')
            ->whereDate('created_at','>=', $start_date)
            ->whereDate('created_at','<=', $end_date)
            ->selectRaw('*, sum(quantity) as sum')
            ->groupBy('product_id')
            ->orderBy('sum', 'desc')
            ->get()
            ->toArray();


        // dd($allData);

        return $allData;
    }


    public function export_data_zone(){

        $allSale = $this->getData();

        $zone = Zone::where('id', Request('zone'))->select('name')->first();

        $allData =  [
            'allSale' => $allSale,
            'zoneName' => $zone->name,
            'date'  => '('. date('d F, Y', strtotime(Request('start_date'))) .' to '.date('d F, Y', strtotime(Request('end_date'))) .') ',
        ];

        
        return Excel::download(new ZoneExport( (object) $allData), 'product-' . time() . '.xlsx');
    }
}

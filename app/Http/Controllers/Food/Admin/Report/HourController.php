<?php

namespace App\Http\Controllers\Food\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Food\Sale;
use App\Models\Food\Outlet;
use App\Models\Food\Zone;
use App\Exports\HourExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;


class HourController extends Controller
{

    public function index(){

        $allData = $this->getData();
        return response()->json($allData, 200);
    }


    public function getData(){

        $zone      = Request('zone', '');
        $by_date   = Request('date', '');

        // get all cv code in a specific zone
        $allCvCodeinZone = Outlet::select('cv_code')->where('zone_id', $zone)->get();
        
        $currentCvCodeArray = [];
        foreach ($allCvCodeinZone as $item) {
            array_push($currentCvCodeArray, $item->cv_code );
        }


        $data = Sale::with('foods:id,name_en,image,type_id','foods.type:id,name_en')->whereNull('free_item');

            if( $zone && $zone != "All" ){
                $data->whereIn('cv_code', $currentCvCodeArray) ;
            }

            $allData = $data->whereDate('created_at', $by_date )
            ->select('id', 'cv_code', 'updated_at', 'quantity')
            ->selectRaw("DATE_FORMAT(created_at, '%h %p') hour, product_id, sum(quantity) as total_sales")
            ->groupBy('hour', 'product_id')
            ->orderBy('total_sales', 'desc')
            ->whereDate('created_at', $by_date)
            ->get()
            ->toArray();


        $newData = self::group_by($allData, 'hour');

    
        $chart_labels = [];
        $chart_data = [];
        $array_chart_data = [];
        

        if( count($newData) == 0 ){
            $newData = null;
        }else{
            foreach ($newData as $key => $value) {
                array_push($chart_labels, $key);
                
                foreach ($value as $value2) {
                    
                    array_push($array_chart_data, $value2['total_sales']);

                }

                array_push($chart_data ,array_sum($array_chart_data));
                $array_chart_data = [];

            }
        }


        $allData = [
           'finalData' => $newData,
           'chart_label' => $chart_labels,
           'chart_data' => $chart_data,
        ];
        

        return $allData;
    }



    public function group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }




    public function export_data_hour(){

        $allData = $this->getData();

        $zone = Zone::where('id', Request('zone'))->select('name')->first();

        $allData +=  [
            'zoneName' => $zone->name,
            'date'  => '('. date('d F, Y', strtotime(Request('date'))) .')',
            
        ];


        return Excel::download(new HourExport( (object) $allData), 'product-' . time() . '.xlsx');
    }
}

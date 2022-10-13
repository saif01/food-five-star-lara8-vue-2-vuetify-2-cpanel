<?php

namespace App\Http\Controllers\Food\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\ProductSale;
use App\Models\Food\Zone;
use App\Models\Food\Outlet;
use App\Http\Controllers\Food\CommonController;

class CommonReportController extends Controller
{
    //product_sale_items
    public function product_sale_items(){
        $allData = ProductSale::with(['type'=>function($q){
            $q->select('id', 'name_en');
        }])->select('id', 'name_en', 'type_id')
        ->where('status', 1)
        ->get()
        ->toArray();
        return response()->json($allData, 200);

    }


    public function get_all_zone(){

        // $allData = Zone::where('status', 1)->select('id AS value', 'name AS text')->get()->toArray();

        $allData = CommonController::GetZonesByAssignedZone();

        // Sort 
        asort($allData);

        $finalArray = [];
        if($allData){
            foreach($allData as $item){
                $finalArray[] = [
                    'value' => $item['id'],
                    'text' => $item['name'],
                ];
            }
        }
        return response()->json($finalArray, 200);
    }

    public function shop(){
        $data = Outlet::where('cv_code', Request('cv_code'))->select('shop_name', 'cv_code', 'shop_address')->first();
        return response()->json($data,200);
    }

}

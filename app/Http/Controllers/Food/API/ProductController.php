<?php

namespace App\Http\Controllers\Food\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;
use DateTime;

class ProductController extends Controller
{
    //index
    public function index($product_code){

        // [{"PRODUCT_CODE":"9X01001273",
        //     "PRODUCT_NAME":"Basket Fryer SS304 L11\"X W9\" X H5.5",
        //     "EFFECTIVE_DATE":"2022-03-02 00:00:00",
        //     "UOM":"Piece",
        //     "EQUIVALENT_KG":"1",
        //     "EQUIVALENT_PC":null,
        //     "VAT_RATE":"05",
        //     "UNIT_PRICE":"2260",
        //     "STATUS":"A"}]

        $responseQuery = null;

        $responseQuery = DB::connection('oracle_db')->table('WA_FOOD_OUTLET_PRODUCT')
        ->where('PRODUCT_CODE', $product_code)
        ->get()
        ->toArray();

        //dd($responseQuery[0]->UNIT_PRICE);

        if(empty($responseQuery)){
            return null;
        }else{
            return $responseQuery[0];
        }
        
    }


    // SmartSoftProductPrice
    public static function SmartSoftProductPrice($product_code){
        $responseQuery = null;

        $responseQuery = DB::connection('oracle_db')->table('WA_FOOD_OUTLET_PRODUCT')
        ->where('PRODUCT_CODE', $product_code)
        ->select('PRODUCT_CODE','UNIT_PRICE')
        ->get()
        ->toArray();

        //dd($responseQuery[0]->UNIT_PRICE);

        if(empty($responseQuery)){
            return null;
        }else{
            return $responseQuery[0];
        }
        
    }



    public static function SmartSoftProductPriceArray($product_code){
        $responseQuery = null;

        $responseQuery = DB::connection('oracle_db')->table('WA_FOOD_OUTLET_PRODUCT')
        ->select('PRODUCT_CODE','UNIT_PRICE')
        ->whereIn('PRODUCT_CODE', $product_code)
        ->orderBy('EFFECTIVE_DATE', 'desc')
        ->get()
        ->unique('PRODUCT_CODE')
        ->toArray();

        if(empty($responseQuery)){
            return null;
        }else{
            return $responseQuery;
        }
        
    }
}

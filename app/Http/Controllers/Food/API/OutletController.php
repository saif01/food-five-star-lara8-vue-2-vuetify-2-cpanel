<?php

namespace App\Http\Controllers\Food\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use DateTime;
use App\Models\Food\SmartsoftOutlet;

class OutletController extends Controller
{
    //index
    public function outlet(){
        // $cv_code = "182189"
        // {"CUSTOMER_CODE":"180109",
        //     "CUSTOMER_NAME":"Chicken Dude (Khilgaon)",
        //     "ADDRESS":"B\/1,Malibagh Chowdhury Para. Dhaka",
        //     "TELEPHONE":"01621778277",
        //     "EMAIL":null,
        //     "LATITUDE":null,
        //     "LONGITUDE":null,
        //     "DIVISION":"Dhaka",
        //     "DISTRICT":"Dhaka",
        //     "ZONE":"FO-Dhaka 2",
        //     "STATUS":"A",
        //     "LAST_UPDATE_DATE":"2022-09-12 09:37:36"}

        $responseQuery = null;

        $responseQuery = DB::connection('oracle_db')->table('WA_FOOD_OUTLET_CUSTOMER')
        // ->where('STATUS', 'A')
        ->get()
        ->toArray();
        

        if(empty($responseQuery)){
           return response()->json(['title'=>'Error', 'msg'=> 'Outlet Data Not Found', 'icon'=>'error'],200); 
        }else{

            $data = SmartsoftOutlet::truncate();

            $newArray = [];
            // FO-Dhaka 1, FO-Dhaka 2, FO-Dhaka 3, FO-CTG

            foreach ($responseQuery as $key => $value) {
                $newArray[] = [
                    "cv_code" => $value->CUSTOMER_CODE,
                    "zone" => $value->ZONE,
                    "shop_name" => $value->CUSTOMER_NAME,
                    "shop_address" => $value->ADDRESS,
                    "number" => $value->TELEPHONE,
                    "latitude" => $value->LATITUDE,
                    "longitude" => $value->LONGITUDE,
                    "email" => $value->EMAIL,
                    "division" => $value->DIVISION,
                    "district" => $value->DISTRICT,
                    "status" => $value->STATUS,
                    "created_at" => Carbon::now()->toDateTimeString(),
                    "updated_at" => Carbon::now()->toDateTimeString(),

                ];
            }


            $chunk_array = array_chunk($newArray, 200);

            foreach ($chunk_array as $key => $value) {

                $status = SmartsoftOutlet::insert($value);

            }
            if($status){

                return response()->json(['title'=>'Successfull', 'msg'=> 'Outlet Sync Successfully', 'icon'=>'success'],200); 
            }else{
                return response()->json(['title'=>'Error', 'msg'=> 'Outlet Sync Failed', 'icon'=>'error'],200); 
            }

        }
        
    }
}

<?php

namespace App\Http\Controllers\Auth\Common;

use Cookie;
use Cache;

trait Authentication {


    // suspiciousLoginCheck
    protected function suspiciousLoginCheck(){

        $XSRFTOKEN =  Cookie::get('XSRF-TOKEN');
       
        $attamed = $this->seCacheByName($cookie_name = $XSRFTOKEN, $data = 1, $life_time_sec = 60);
        
        if($attamed >= 5){
            $acurateAttamed = round($attamed/2);
            return $acurateAttamed;
        }else{
            return false;
        }
    }


    // seCacheByName
    protected function seCacheByName($name_of_key = null, $data = 1, $life_time_sec = 300){

         
        if( Cache::has($name_of_key) ){
            //$currentData = Cache::get($name_of_key);
            // Incrementer Data
            Cache::increment($name_of_key, 1);
            //$data = $currentData + 1;
        }else{
            // Set data
            Cache::put($name_of_key, 1, $life_time_sec);
        }

        

       return Cache::get($name_of_key);

    }


}
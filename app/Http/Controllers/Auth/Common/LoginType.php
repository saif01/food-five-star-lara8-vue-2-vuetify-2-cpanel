<?php

namespace App\Http\Controllers\Auth\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginType extends Controller
{
    //
    public static function CHECK($login = null){

        if($login){
            $checkType      = gettype($login);
            $checkLength    = strlen($login);
            $strt2Chr       = substr($login, 0, 2);
            $is_numeric     = is_numeric($login);
            

            //dd($login, $checkType, $checkLength, $strt2Chr, $is_numeric);
            $user_type = '';
            // Check Login type
            if($login && $checkType == "string" && $checkLength && $strt2Chr){

                if($is_numeric && $checkLength == 11 && $strt2Chr == '01' ){
                    $user_type = 'wn';
                }elseif($is_numeric && $checkLength == 6 ){
                    $user_type = 'op';
                }elseif(!$is_numeric && $checkLength > 3){
                    $user_type = 'ad';
                }
            }

            return [ 'status'=>true, 'type' => $user_type, 'login'=> $login ];

        }else{
            return [ 'status'=>false,'type' => null, 'login'=> $login ];
        }
        
    }
}

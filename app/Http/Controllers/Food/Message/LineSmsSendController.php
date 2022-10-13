<?php

namespace App\Http\Controllers\Food\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LineSmsSendController extends Controller
{
    //
    public static function test(){

        $msg = "Cpanel Schedule Running (uat-fo.cpbfivestar.com)";

        self::lineMsg($msg);
        return true;
    }


    //Line Message send
    public static function lineMsg($message, $key_name='line_test_key')
    {
        $keyVarName = "values.".$key_name;

        $chOne = curl_init();
        curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        // SSL USE
        curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
        //POST
        curl_setopt($chOne, CURLOPT_POST, 1);
        // Message
        curl_setopt($chOne, CURLOPT_POSTFIELDS, $message);
        //��ҵ�ͧ�������ػ ������ 2 parameter imageThumbnail ���imageFullsize
        curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=$message");
        // follow redirects
        curl_setopt($chOne, CURLOPT_FOLLOWLOCATION, 1);

        // //Test Group
        // $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.config('values.line_test_key'),);  // ��ѧ����� Bearer ��� line authen code �

        //Car Booking Group
        $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.config($keyVarName),);  // ��ѧ����� Bearer ��� line authen code � env('APP_DEBUG')
        
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
        //RETURN
        curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($chOne);

        //Check error
        if (curl_error($chOne)) {
            echo 'error:' . curl_error($chOne);
        } else {
            $result_ = json_decode($result, true);

            //************Status Print *************//

            //echo "status : ".$result_['status']; echo "message : ". $result_['message'];
            //echo "SMS send Successfully";
        }
        //Close connect
        curl_close($chOne);
    }
}

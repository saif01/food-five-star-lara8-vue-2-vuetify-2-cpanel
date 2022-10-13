<?php

namespace App\Http\Controllers\Food\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Food\Sale;
use App\Models\Food\Outlet;
use App\Models\Food\SalesSummury;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Food\MessageTemplate;

class IndexController extends Controller
{
    // SentOwnerDailyMessage
    public static function SentOwnerDailyMessage(){

        $allData = User::where('user_type', 'wn')->whereNotNull('cv_code_owner')->where('status', 1)->select('user_type', 'cv_code_owner', 'owner_login')->get();

        // currentCvCode
        $allCvCodeinObject = [];
        foreach ($allData as $key => $value) {

            if($value->cv_code_owner){
                $cvCodeArray = explode(",", $value->cv_code_owner);
                $ownerNumber = $value->owner_login;
                // get all outlet against code 
                $outletData = Outlet::where('status', 1)->whereIn('cv_code',$cvCodeArray)->select('cv_code','owner_mobile','owner','shop_name')->get();

                foreach ($outletData as $key => $outletItem) {

                    $allSaleAmount = SalesSummury::where('cv_code', $outletItem->cv_code)
                        ->whereDate('sales_date', Carbon::now()->toDateString())
                        ->selectRaw('cv_code, sum(total_price) as total_sale')
                        ->groupBy('cv_code')
                        ->first();

                        // dd($ownerNumber, $allSaleAmount, $outletItem);

                    if($allSaleAmount){
                        $message = "Your total sale for " . Carbon::now()->format('M d, Y') . " is: " . $allSaleAmount->total_sale . "/= Taka  - for your shop (".$outletItem->shop_name."). Thank you.";
                        self::sendSms($ownerNumber, $message);
                    }

                }


            }

            return true;
            
        }

        
    }

    // Send daile sale SMS
    public static function dailySaleessage($amount, $shop_name){
        $msgData = MessageTemplate::where('status', 1)->where('title', 'daily-sale-owner')->select('message')->first();

        if($msgData->message){
            if(strstr( $msgData->message, '{outlet_name}' ) && strstr( $msgData->message, '{date}' ) && strstr( $msgData->message, '{amount}' ) ){

                $replaceOutletName = str_replace("{outlet_name}","$shop_name",$msgData->message);

                $replaceDate = str_replace("{date}",Carbon::now()->format('M d, Y'),$replaceOutletName);

                $replaceAmount = str_replace("{amount}",$amount,$replaceDate);

                $msg = $replaceAmount;

            }else{
                $msg = 'Your total sale for ' . Carbon::now()->format('M d, Y') . ' is: " 0.00 Taka " - for your shop ('.$shop_name.'). Thank you.';
            }

        }else{
            $msg = 'Your total sale for ' . Carbon::now()->format('M d, Y') . ' is: " 0.00 Taka " - for your shop ('.$shop_name.'). Thank you.'; 
        }

        return $msg;
        

    }



    // public function to Sent Message
    public function SentMessage($number = null,$message = null){

        $res = self::sendSms($number, $message);
        
        return $res;
    }


    // public function to Sent Message
    public function SentOrderConfirmMessage($number = null,$message = null){

        $res = self::sendSms($number, $message);

        return $res;
    }



     // send sms
    protected static function sendSms($number = null,$message = null,$allSaleAmount = null){

        if($number){
            $response = Http::asForm()->post('https://gpcmp.grameenphone.com/gpcmpbulk/messageplatform/controller.home', [
                'username'=>'CPBDAdmin',
                'password'=>'CPBit123!@',
                'apicode'=>5,
                'msisdn'=>$number,
                'countrycode'=>'880',
                'cli'=>'CPB',
                'messagetype'=>1,
                'message'=>$message,
                'messageid'=>0,
            ]);
            //return $response;

            if($response->status() == 200){
                return true;
            }else{
                return false;
            }
        }
        

    }


}

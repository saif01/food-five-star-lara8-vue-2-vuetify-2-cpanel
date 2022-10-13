<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Auth\Common\LoginType;
use App\Models\Food\OtpSend;
use App\Models\Food\Outlet;
use App\Models\Food\MessageTemplate;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use App\Http\Controllers\Food\Message\IndexController;

class ResetController extends Controller
{
    //reset_pass
    public function reset_pass(Request $request){
        
        $this->validate($request,[
            'login'     => 'required|max:20',
        ]);

        $login      = $request->login;

        //dd($login);
        $login_type = LoginType::CHECK($login);

        //dd($login_type);

        if($login_type && !empty($login_type['type']) && $login_type['status']){
            $user_type = $login_type['type'];
        }else{
            $allData = [
                'status'    => 'error',
                'msg'       => 'Invalid ID, Please Put Correct ID',
                'data'      => '',
            ];
            return response()->json($allData);
        }


        if($user_type == 'wn'){
            $data = User::where('owner_login', $login)->where('status', 1)->select('id','owner_login')->first();
            
            if(!$data){
                
                $allData = [
                    'status'    => 'error',
                    'msg'       => 'Owner Number Not Found',
                    'data'      => '',
                ];
                return response()->json($allData);
            }

            

        }elseif($user_type == 'op'){

            $data = User::where('cv_code', $login)->where('status', 1)->first();
            // dd($data);

            if($data){
                $data = User::where('user_type', 'wn')->where('cv_code_owner', 'like', '%'. $login .'%')->where('status', 1)->select('id','owner_login')->first();
            }else{
                $allData = [
                    'status'    => 'error',
                    'msg'       => 'CV Code Not Found',
                    'data'      => '',
                ];
                return response()->json($allData);
            }
            

        }elseif($user_type == 'ad'){
            $allData = [
                'status'    => 'error',
                'msg'       => 'Admin ID Password can not reset from here. Contact with IT Department',
                'data'      => '',
            ];
            return response()->json($allData);
        }else{
             $allData = [
                'status'    => 'error',
                'msg'       => 'Please, Put the correct information.',
                'data'      => '',
            ];
            return response()->json($allData);
        }

        //dd($data->owner_login, $data);

        $number = $data->owner_login;

        //dd($number);

        if($data && $number){

            $optData = new OtpSend();
            $generate_otp       = random_int(1000, 9999);
            $optData->number    = $number;
            $optData->otp       = $generate_otp;
            $optData->expiry    = Carbon::now()->addMinutes(30)->toDateTimeString();
            $success            = $optData->save();

            //dd($login);

            // get outlet name
            if($user_type == 'wn'){
                $outlet_name = Outlet::where('owner_mobile', $login)
                ->orWhere('owner_mobile_2', $login)
                ->select('shop_name', 'shop_address')
                ->first();
            }else{
                $outlet_name = Outlet::where('cv_code', $login)->select('shop_name', 'shop_address')->first();
            }
            

            // dd($outlet_name->shop_name);

            if($outlet_name){
                if($outlet_name->shop_name){
                    if($success){
                        
                        // operator message
                        if($user_type == 'op'){

                            // get msg from message template
                            $msgData = MessageTemplate::where('status', 1)->where('title', 'reset-password')->select('message')->first();

                            if($msgData->message){

                                if(strstr( $msgData->message, '{otp}' ) && strstr( $msgData->message, '{outle_name}' ) && strstr( $msgData->message, '{outlet_address}' ) ){

                                    $replaceOtp = str_replace("{otp}","$generate_otp",$msgData->message);
                                    $replaceOutletName = str_replace("{outlet_name}","$outlet_name->shop_name",$replaceOtp);
                                    $replaceAddress = str_replace("{outlet_address}","$outlet_name->shop_address",$replaceOutletName);
                                    $msg = $replaceAddress;

                                }else{
                                    $msg = 'Your security code is: '.$generate_otp.' for your outlet ('.$outlet_name->shop_name.', shop '.$outlet_name->shop_address.'). Thank you.';
                                }

                            }else{
                                $msg = 'Your security code is: '.$generate_otp.' for your outlet ('.$outlet_name->shop_name.', shop '.$outlet_name->shop_address.'). Thank you.';
                            }

                        }

                        // owner messasge
                        if($user_type == 'wn'){

                            $msg = 'Your security code is: '.$generate_otp.' . Thank you.';

                        }

                        

                        $res = IndexController::SentMessage($number,$msg);

                        // dd($res);

                        if( $res== true){
                            $allData = [
                                'status'    => 'success',
                                'msg'       => 'An OTP has been sent to '.$number.' this number',
                                'data'      => $number,
                                'reset_type' => $user_type,
                                'cv_code'   => $login
                            ];
                            return response()->json($allData);
                        }
                    }
                }else{
                    if( $res== true){
                        $allData = [
                            'status'    => 'error',
                            'msg'       => 'Shop Information not found',
                        ];
                        return response()->json($allData);
                    }
                }
            }else{
                $allData = [
                    'status'    => 'error',
                    'msg'       => 'Shop Information not found',
                ];
                return response()->json($allData);
            }
            


        }else{
            $allData = [
                'status'    => 'error',
                'msg'       => 'Invalid ID, Please Put Correct ID',
                'data'      => '',
            ];
            return response()->json($allData);
        }

        
    }


    
    // send sms
    // protected static function sendSms($message = null , $number = null){
    //     //dd($message);
    //     if($number){
    //         $response = Http::asForm()->post('https://gpcmp.grameenphone.com/gpcmpbulk/messageplatform/controller.home', [
    //             'username'=>'CPBDAdmin',
    //             'password'=>'CPBit123!@',
    //             'apicode'=>5,
    //             'msisdn'=>$number,
    //             'countrycode'=>'880',
    //             'cli'=>'CPB',
    //             'messagetype'=>1,
    //             'message'=>$message,
    //             'messageid'=>0,
    //         ]);
    //         //return $response;

    //         if($response->status() == 200){
    //             return true;
    //         }else{
    //             return false;
    //         }
    //     }
        

    // }


    public function verify_otp(Request $request){
        

        if($request->number && $request->otp){
            $data = OtpSend::where('number', $request->number)->whereNull('status')->orderBy('id','desc')->first();

            if($data){
                // get owner id
                $owner_id = User::where('owner_login', $request->number)->select('id')->first();

                //dd($owner_id);
                // if in expire time
                if($data->expiry >= Carbon::now()->toDateTimeString()){

                    // if otp match
                    if($data->otp == $request->otp){
                        $allData = [
                            'status'    => 'success',
                            'msg'       => 'OTP Verified',
                            'data'      => '',
                            'owner_id'  => $owner_id->id,
                        ];
                        $data->status = 1;
                        $data->save();
                        return response()->json($allData);
                    }else{
                        // otp does not match
                        $allData = [
                            'status'    => 'error',
                            'msg'       => 'OTP does not match',
                            'data'      => '',
                        ];
                        return response()->json($allData);
                    }
                }else{
                    // otp expired
                    $allData = [
                        'status'    => 'error',
                        'msg'       => 'OTP has been expired',
                        'data'      => '',
                        'expire'    => 'expired'
                    ];
                    $data->status = 1;
                    $data->save();
                    return response()->json($allData);
                }
            }else{
                $allData = [
                    'status'    => 'error',
                    'msg'       => 'Data not found',
                    'data'      => '',
                ];
                return response()->json($allData);
            }
        }
    }


    public function new_password(Request $request){
        

        $this->validate($request,[
            
            'new_pass'      => 'required|min:6',
            'confirm_pass'  => 'required|same:new_pass|min:6',
            'owner_id'      => 'required',
            'reset_type'    => 'required',
            'cv_code'       => 'nullable'
        ]);


        if($request->reset_type == 'wn'){

            $data = User::find($request->owner_id);

        }elseif($request->reset_type == 'op'){

            $data = User::where('cv_code',$request->cv_code)->where('user_type', $request->reset_type)->first();

        }



        if($data){
            $data->password = $request->confirm_pass;
            $success        = $data->save();

            if($success){
                $allData = [
                    'status'    => 'success',
                    'msg'       => 'Password Successfully Reseted',
                    'data'      => '',
                ];
                return response()->json($allData);
            }
        }
    }


    // public function test_sms(){
    //    return self::sendSms('This is test sms', '01707080401');
    // }
        
        
}


<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\User;
use Cookie;
use Cache;
use App\Http\Controllers\Auth\Common\Authentication;
use App\Http\Controllers\Auth\Common\Log;
use App\Http\Controllers\Auth\Common\ADLogin;
use App\Http\Controllers\Auth\Common\LoginType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class LoginController extends Controller
{

    use Authentication;

    // login_action
    public function login_action(Request $request){
            
        // Validations
        request()->validate([
            'login'     => 'required|max:20',
            'password'  => 'required|max:40',
            //'user_type' => 'required',
        ]);

        $login      = $request->login;
        $password   = $request->password;
        //$user_type  = $request->user_type;

        $login_type = LoginType::CHECK($login);

        if($login_type && $login_type['status']){
            $user_type = $login_type['type'];
        }else{
            $allData = [
                'status'    => 'error',
                'msg'       => 'Invalid Username or Password',
                'data'      => '',
            ];
            return response()->json($allData);
        }


        // login attempts more than five times in a minute
        if( $this->suspiciousLoginCheck() ){
                $allData = [
                    'status'    =>'error',
                    'code'      => 208,
                    'msg'       =>'You are locked for 60 seconds ! Try later',
                    'attempts'  => $this->suspiciousLoginCheck()
                ];
            return response()->json($allData);
        }

        //dd( config('values.app_debug'), env('APP_DEBUG') );
       if(! config('values.app_debug') ){
            // Production mode App debug false
            if($user_type == 'ad'){
                // AD Login
                $response = $this->adLogin($login, $password, $user_type);
            }elseif($user_type == 'wn'){
                // Owner Login
                $response = $this->OthersLogin($login, $password, $user_type);
            }elseif($user_type == 'op'){
                // Operator Login
                $response = $this->OthersLogin($login, $password, $user_type);
            }
            

        }else{
            // local mode App debug true
            $response = $this->localLogin($login, $user_type);

        }

         //dd($login_type, $response);


        return response()->json($response, 200);

    }


    // Local login
    private function localLogin($login =null, $user_type = null){

        if($user_type == 'ad'){
            $allData = User::where('login', $login)->first();

            if( empty($allData) ){
                return  $response = (object) [
                    'status'    => 'error',
                    'msg'       => 'You are not authorized as Admin',
                    'data'      => $allData,
                ];
            }

        }elseif($user_type == 'op'){
            $allData = User::where('cv_code', $login)->first();

            if( empty($allData) ){
              return  $response = (object) [
                    'status'    => 'error',
                    'msg'       => 'You are not authorized as Operator',
                    'data'      => $allData,
                ];
            }

        }elseif($user_type == 'wn'){
            $allData = User::where('owner_login', $login)->first();

            if( empty($allData) ){
                return  $response = (object) [
                    'status'    => 'error',
                    'msg'       => 'You are not authorized as Owner',
                    'data'      => $allData,
                ];
            }

        }else{
            return  $response = (object) [
                    'status'    => 'error',
                    'msg'       => 'Invalid Credential',
                    'data'      => null,
                ];
        }

        if($allData){

            if($allData->status == 1){

                //Local Server Authentication passed...
                Auth::login($allData);

                // Store Login Log status code 1 everything ok
                Log::Store($login, 1);

                $response = (object) [
                    'status'    => 'success',
                    'msg'       => 'Data found in CP Five Star',
                    'data'      => $allData,
                    //'user_type' => $user_type,
                ];
                 return $response;

            }else{

                // Store Login Log status code 2 user blocked
                Log::Store($login, 2);
                // Logout
                Auth::logout();

                $response = (object) [
                    'status'    => 'error',
                    'msg'       => 'Your ID was blocked by CP Five Star!',
                    'data'      => null,
                ];
                 return $response;
            }

            

        }else{

            
            Auth::logout();
            // Store Login Log status code 0 means Data not found in CP Five Star
            Log::Store($login, 0);

            $response = (object) [
                'status'    => 'error',
                'msg'       => 'You are not authorized for CP Five Star',
                'data'      => $allData,
            ];
             return $response;

        }

    }


    // Others Login
    private function OthersLogin($login =null, $password=null, $user_type=null){


        if($user_type && $login && $password){
            if($user_type == 'op'){
                $allData = User::where('cv_code', $login)->where('password', $password)->first();

                $data = User::where('cv_code', $login)->first();

                // need to register operator
                if(empty($data)){
                    Log::Store($login, 0);

                    return  $response = (object) [
                        'status'    => 'error',
                        'msg'       => 'You are not authorized as Operator',
                        'data'      => $allData,
                    ];
                }

                if( empty($allData) ){
                    
                    Log::Store($login, 4);

                    return  $response = (object) [
                        'status'    => 'error',
                        'msg'       => 'You are not authorized as Operator',
                        'data'      => $allData,
                    ];
                }

            }elseif($user_type == 'wn'){
                $allData = User::where('owner_login', $login)->where('password', $password)->first();

                $data = User::where('owner_login', $login)->first();

                // need to register operator
                if(empty($data)){
                    Log::Store($login, 0);

                    return  $response = (object) [
                        'status'    => 'error',
                        'msg'       => 'You are not authorized as Operator',
                        'data'      => $allData,
                    ];
                }

                if( empty($allData) ){

                    Log::Store($login, 5);

                    return  $response = (object) [
                        'status'    => 'error',
                        'msg'       => 'You are not authorized as Owner',
                        'data'      => $allData,
                    ];
                }
            }else{

                Log::Store($login, 6);

                return  $response = (object) [
                    'status'    => 'error',
                    'msg'       => 'Invalid Credential',
                    'data'      => null,
                ];
            }
        }else{

            $response = (object) [
                'status'    => 'error',
                'msg'       => 'Data found in CP Five Star',
                'data'      => $allData,
            ];
        }

        


        

        if($allData){


            if($allData->status == 1){


                //Local Server Authentication passed...
                Auth::login($allData);

                // Store Login Log status code 1 everything ok
                Log::Store($login, 1);

                $response = (object) [
                    'status'    => 'success',
                    'msg'       => 'Data found in CP Five Star',
                    'data'      => $allData,
                    //'user_type' => $user_type,
                ];
                // return $response;

            }else{

                // Store Login Log status code 2 user blocked
                Log::Store($login, 2);
                // Logout
                Auth::logout();

                $response = (object) [
                    'status'    => 'error',
                    'msg'       => 'Your ID was blocked by CP Five Star!',
                    'data'      => '',
                ];
                // return $response;
            }

        }else{

            
            Auth::logout();
            // Store Login Log status code 0 means Data not found in CP Five Star
            Log::Store($login, 0);

            $response = (object) [
                'status'    => 'error',
                'msg'       => 'You are not authorized for CP Five Star',
                'data'      => $allData,
            ];
            

        }
        
        
    

        return $response;

    }

   





    


    // AD Login
    private function adLogin($login =null,$password = null,$user_type=null){


        // Login Check By AD
        // $adResponse = ADLogin::Data($login, $password);

        $response = Http::asForm()->post('http://it.cpbangladesh.com/api/ad_auth', [
            'login' => $login,
            'pass' => $password,
        ]);
        $adResponse = $response->object();
        $adData = $adResponse->data;

        // dd($adResponse->status, $adData);


        if($adResponse->status == 'success'){

            $response = $this->localLogin($login, $user_type);

        
            // dd( $adData, $localData, $response, $adResponse);

            if( $response->status == 'success' ){

                // Local DB data
                $localData  = $response->data;
                // AD response data
                $adData     = $adResponse->data;

                // dd($adData, $localData);

                $localData->name             = $adData->UserName;
                $localData->department       = $adData->Department;
                $localData->office_id        = $adData->OfficeID;
                $localData->office_contact   = $adData->OfficeMobile;
                $localData->personal_contact = $adData->PersonalMobile;
                $localData->office_email     = $adData->OfficeEmail;
                $localData->personal_email   = $adData->PersonalEmail;
                $localData->office           = $adData->Office;
                $localData->zone_office      = str_replace('&', 'and', $adData->Office);
                $localData->business_unit    = $adData->BusinessUnit;
                $localData->nid              = $adData->NationalID;
                $localData->save();

                $response = (object) [
                    'status'    => 'success',
                    'msg'       => 'Data found in CP Five Star & Sync.',
                    'data'      => $localData,
                ];
                return $response;

            }else{

                return $response;

            }

        
        }else{
            // Store Login Log status code 3 user Not Found AD 
            Log::Store($login, 3);

            $response = (object) [
                'status'    => 'error',
                'msg'       => 'You are not authorized for AD',
                'data'      => '',
            ];
            return $response;
        }
        


    }




    // logout
    public static function logout(){
        if(Auth::check()){
            $data = User::findOrFail(Auth::user()->id);
            $data->last_activity = Carbon::now()->format('Y-m-d H:i');
            //$data->last_activity = Carbon::now()->addMinute('5')->format('Y-m-d H:i');
            $data->save();
            // Logout Log Update
            Log::LogoutLog();
        }
        Session::flush();
        Auth::logout();
        return redirect()->to('login');
    }
    
}

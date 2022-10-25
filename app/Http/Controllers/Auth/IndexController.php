<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Http\Controllers\Food\CommonController;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\Common\ADLogin;
use Illuminate\Support\Facades\Http;

class IndexController extends Controller
{
    //index
    public function index(){

        if( Auth::check() ){
            return redirect()->route('dashboard');
        }

        $maintenance = 'pppffnnerrrr'; 
        if(CommonController::IsMaintenance()){
            $maintenance = 'safsdfdsyyyasfs'; 
        }

        //dd($maintenance);

        return view('auth.index', compact('maintenance'));
    }




    // auth_check
    public function auth_check(){
        if( Auth::check() ){
            $dataBlockCheck = User::whereNull('status')->where('id', Auth::user()->id)->first();
            // $data->last_activity = Carbon::now()->format('Y-m-d H:i');
            if(!empty($dataBlockCheck)){
                LoginController::logout();
                return false;
            }

            $data = User::findOrFail(Auth::user()->id);
            if($data){
                // $data->last_activity = Carbon::now()->format('Y-m-d H:i');
                $data->last_activity = Carbon::now()->addMinute('5')->format('Y-m-d H:i');
                $data->save();
            }
            return true;
        }
        return false;
    }


    // ad_check
    public function ad_check(Request $request){
        $login = 'syful.isl';
        $password = 'Saif5683@9';

        $response = Http::asForm()->post('http://it.cpbangladesh.com/api/ad_auth', [
            'login' => $login,
            'pass' => $password,
        ]);

     
        $adResponse = $response->object();
        $adData = $adResponse->data;

        dd($adResponse);


    }

}

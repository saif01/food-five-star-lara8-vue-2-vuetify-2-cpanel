<?php

namespace App\Http\Controllers\Food\Owner\Reset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class IndexController extends Controller
{
    // reset
    public function index(Request $request){
        $this->validate($request,[
            
            'current_pass'  => 'required',
            'new_pass'      => 'required|min:6',
            'confirm_pass'  => 'required|same:new_pass|min:6',
        ]);

        $data = User::find(Auth::user()->id);


        if($data){

            if($data->password == $request->current_pass){
                $data->password = $request->confirm_pass;
                $success        = $data->save();

                if($success){
                    $allData = [
                        'status'    => 'success',
                        'msg'       => 'Password Successfully Reseted',
                        'data'      => '',
                    ];
                    
                }

            }else{
                $allData = [
                    'status'    => 'error',
                    'msg'       => 'Old Password Not Match',
                    'data'      => '',
                ];
            }

            return response()->json($allData);

            
            
        }
    }
}

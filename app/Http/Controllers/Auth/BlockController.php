<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;

use App\Http\Controllers\Auth\LoginController;

class BlockController extends Controller
{
    public function block_user($id){

        $data = User::findOrFail($id);

        if($data){
            $data->status = null;
            $success = $data->save();

            LoginController::logout();
        }

    } 
}

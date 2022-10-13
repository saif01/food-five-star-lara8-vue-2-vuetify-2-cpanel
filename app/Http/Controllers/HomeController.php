<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
     public function index()
    {
        // $role = Auth::user()->role;
        // $checkrole = explode(',', $role);
        // if (in_array('admin', $checkrole)) {
        //     return redirect('dog/indexadmin');
        // } else {
        //     return redirect('pet/index');
        // }

        // if(Auth::user()->hasAdmin()){
            return $this->redirectTo = route('admin.dashboard') ;
            
        // }

    }
}

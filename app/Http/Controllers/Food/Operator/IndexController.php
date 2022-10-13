<?php

namespace App\Http\Controllers\Food\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use App\Models\Food\UserLanguage;
use App\Models\Food\Announcement;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function Index(){

        $data = UserLanguage::where('user_id', Auth::user()->id)->first();
        if($data){
            $language = $data->language;
        }else{
            $language = '';
        }

        $roles = '';

        return view('food.operator.index', compact('roles', 'language'));
    }

    // get_announcement
    public function get_announcement()
    {
        $date = Carbon::today()->toDateString();
        $allData = Announcement::whereIn('allow', ['operator', 'all'])->where('status', 1)->whereDate('start','<=', $date)->whereDate('end','>=', $date)->select('image')->get()->toArray();
        return response()->json($allData, 200);
    }

    // setLanguage
    public function setLanguage($lang){

        $allData = UserLanguage::where('user_id', Auth::user()->id)->first();

        if(!$allData){
            $allData = new UserLanguage();
        }

        $allData->user_id     = Auth::user()->id;
        $allData->language    = $lang;
        $success              = $allData->save();

        return response()->json($allData, 200);
        
    }

}

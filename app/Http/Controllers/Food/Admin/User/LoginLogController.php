<?php

namespace App\Http\Controllers\Food\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserLoginLog;
use App\Models\User;
use Carbon\Carbon;

class LoginLogController extends Controller
{
    //all
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $short_by       = Request('short_by', 'id');
        $start    = Request('start', '');
        $end     = Request('end', '');
        

        $allDataQuery = UserLoginLog::select('id', 'status', 'login_id', 'os', 'browser', 'device', 'created_at', 'updated_at')->orderBy($sort_field, $sort_direction);

        if( !empty($short_by) ){
            if($short_by == 's'){
                $allDataQuery->where('status', 1);

            }elseif($short_by == 'ae'){
                 // AD Server Error
                $allDataQuery->where('status', 3);

            }elseif($short_by == 'op'){
                 //code 4 operator login error
                $allDataQuery->where('status', 4);

            }elseif($short_by == 'ow'){
                 //code 4 owner login error
                $allDataQuery->where('status', 5);

            }
        }

        if( !empty($start) && !empty($end) ){
            $allDataQuery->whereBetween('created_at' ,[$start ." 00:00:00", $end." 23:59:59"]);
        }



        $allData = $allDataQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) )
                  ->paginate($paginate);


        // Total Admin Online
        $total_online = User::whereNotNull('last_activity')
        ->whereDate('last_activity', '>=', Carbon::now())
        ->whereTime('last_activity', '>=', Carbon::now())
        ->count();

        $custom = collect( ['total_online' => $total_online] );
        $allData = $custom->merge($allData);

        return response()->json($allData, 200);

    }
}

<?php

namespace App\Http\Controllers\Food\Admin\Outlet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\Outlet;
use App\Models\Food\Zone;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use App\Models\User;
use App\Models\Food\ProductSale;

class IndexController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $by_zone        = Request('by_zone', null);

        $outlets = Outlet::with('zone', 'manager', 'officer', 'makby:id,name')
                ->whereNull('deleted_temp')
                ->orderBy($sort_field, $sort_direction)
                ->search( trim(preg_replace('/\s+/' ,' ', $search)) );

        if( $by_zone && $by_zone != "All" ){
             $outlets->where('zone_id', $by_zone);
        }
        
        $allData = $outlets->paginate($paginate);

        return response()->json($allData, 200);

        

    }


    // store
    public function store(Request $request){

        //dd("store");

        //Validate
        $this->validate($request,[
            'cv_code'          => 'required|unique:outlets',
            'zone_id'          => 'nullable',
            'manager_id'       => 'nullable',
            'officer_id'       => 'nullable',
            'shop_name'        => 'required|string',
            'shop_address'     => 'nullable|string',
            'owner'            => 'nullable|string',
            'owner_mobile'     => 'nullable',
            'owner_mobile_2'   => 'nullable',
            'city'             => 'nullable',
            'district'         => 'nullable',
            'division'         => 'nullable',
            'latitude'         => 'nullable',
            'longitude'        => 'nullable',
        ]);

        $data = new Outlet();

        $data->cv_code          = $request->cv_code;
        $data->zone_id          = $request->zone_id;
        $data->manager_id       = $request->manager_id;
        $data->officer_id       = $request->officer_id;
        $data->shop_name        = $request->shop_name;
        $data->shop_address     = $request->shop_address;
        $data->owner            = $request->owner;
        $data->owner_mobile     = $request->owner_mobile;
        $data->owner_mobile_2   = $request->owner_mobile_2;
        $data->city             = $request->city;
        $data->district         = $request->district;
        $data->division         = $request->division;
        $data->latitude         = $request->latitude;
        $data->longitude        = $request->longitude;
        $data->created_by       =  Auth::user()->id;
        $success                = $data->save();

        if($success){
            return response()->json(['msg'=>'Stored Successfully &#128513;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }


    // update
    public function update(Request $request, $id){

        //Validate
        $this->validate($request,[
            'cv_code'          => 'required|unique:outlets,cv_code,'.$id,
            'zone_id'          => 'nullable',
            'manager_id'       => 'nullable',
            'officer_id'       => 'nullable',
            'shop_name'        => 'required|string',
            'shop_address'     => 'nullable|string',
            'owner'            => 'nullable|string',
            'owner_mobile'     => 'nullable',
            'owner_mobile_2'   => 'nullable',
            'city'             => 'nullable',
            'district'         => 'nullable',
            'division'         => 'nullable',
            'latitude'         => 'nullable',
            'longitude'        => 'nullable',
        ]);

        $data = Outlet::find($id);


        $data->cv_code          = $request->cv_code;
        $data->zone_id          = $request->zone_id;
        $data->manager_id       = $request->manager_id;
        $data->officer_id       = $request->officer_id;
        $data->shop_name        = $request->shop_name;
        $data->shop_address     = $request->shop_address;
        $data->owner            = $request->owner;
        $data->owner_mobile     = $request->owner_mobile;
        $data->owner_mobile_2   = $request->owner_mobile_2;
        $data->city             = $request->city;
        $data->district         = $request->district;
        $data->division         = $request->division;
        $data->latitude         = $request->latitude;
        $data->longitude        = $request->longitude;
        $data->created_by       = Auth::user()->id;
        $success                = $data->save();

        if($success){
            return response()->json(['msg'=>'Updated Successfully &#128515;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }


    // destroy
    public function destroy($id)
    {
        $data       =  Outlet::find($id);
        $success    =  $data->delete();
        return response()->json('success', 200);
    }

    // delete_temp
    public function delete_temp($id){


        $data               = Outlet::findOrFail($id);
        $data->deleted_temp = 1;
        $data->deleted_by    = Auth::user()->id;
        $data->updated_at   = Carbon::now();
        $success            = $data->save();

        if($success){
            return response()->json(['msg'=>'Deleted Successfully &#128515;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data is not deleted !!'
            ], 422);
        }
    }


    // status
    public function status($id){

        $data       =  Outlet::find($id);
        if($data){

           $status = $data->status;
           
            if($status == 1){
                $data->status = null;
            }else{
                $data->status = 1;
            }
            $success    =  $data->save();
            return response()->json('success', 200);

        }

    }

    // check_cv_code
    public function check_cv_code(){

        $cv_code = Request('cv_code');

        //dd('ok', $cv_code);

        $allData = Outlet::where('cv_code', $cv_code)->count();

        if($allData >0){
            $found = 'y';
        }else{
            $found = 'n';
        }

        return response()->json($found, 200);

    }

    public function get_manager_and_officer(){

        $users = User::query()->where('status', 1)->where('user_type', 'ad')->select('id', 'name', 'image');
        $managers = (clone $users)
        ->where('officer_type', 'mg')
        ->get();

        $officers = (clone $users)
        ->where('officer_type', 'of')
        ->get();
        return response()->json(['managers'=>$managers, 'officers'=>$officers], 200);
    }


    

}

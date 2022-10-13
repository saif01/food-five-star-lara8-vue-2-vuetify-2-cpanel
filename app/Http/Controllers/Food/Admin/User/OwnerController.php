<?php

namespace App\Http\Controllers\Food\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Food\Role;
use App\Models\Food\Outlet;
use App\Models\Food\UserRole;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class OwnerController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $search_field   = Request('search_field', '');

        $userTblQuery = User::query()->whereNull('deleted_temp')->whereNotNull('owner_login');

        $allDataQuery = (clone $userTblQuery)->with('roles');

        // Search
        if(!empty($search_field) && $search_field != 'All'){
            $val = trim(preg_replace('/\s+/' ,' ', $search));
            $allDataQuery->where($search_field, 'LIKE', '%'.$val.'%');
        }else{
            $allDataQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) );
        }

        $allData = $allDataQuery->orderBy($sort_field, $sort_direction)
                ->paginate($paginate);


        // Append Filed Data
        foreach($allData as $item){
            $cv_code_array = null;
            $cv_code_outlets = null;
            $makby = null;
            $online = false;
           
            // cv_code_array
            if($item->cv_code_owner){
               $cv_code_array =  explode(',', $item->cv_code_owner);
                // cv_code_outlets
                if(!empty($cv_code_array)){
                    foreach($cv_code_array as $cv_code){
                        $outletData = Outlet::where('cv_code', $cv_code)->select('cv_code', 'shop_name', 'shop_address')->first();
                        if($outletData){
                            $cv_code_outlets[] =  $outletData;
                        }
                        
                    }
                }
            }

            if($item->status_by){
                $makby = User::where('id', $item->status_by)->select('name')->first();
            }

            if($item->last_activity){
                $nowTime = Carbon::now();
                if( $nowTime->lessThanOrEqualTo($item->last_activity) ){
                    $online = true;
                }
            }
            // Append
            $item->cv_code_outlets =  $cv_code_outlets;
            $item->cv_code_array =  $cv_code_array;
            $item->makby =  $makby;
            $item->online =  $online;
        }

        // Total Admin Online
        $total_online = (clone $userTblQuery)->whereNotNull('last_activity')
        ->whereDate('last_activity', '>=', Carbon::now())
        ->whereTime('last_activity', '>=', Carbon::now())
        ->count();
        
        $custom = collect( ['total_online' => $total_online] );
        $allData = $custom->merge($allData);

        return response()->json($allData, 200);

    }


    // store
    public function store(Request $request){

        //Validate
        $this->validate($request,[
            'owner_login'           => 'required|string|max:11|unique:users',
            'name'                  => 'required|string|max:100',
            'cv_code_owner'         => 'required',
            'password'              => 'required|string|max:20',
            'image'                 => 'required',
        ]);

        //dd($request->all());

        $data = new User();

      
        if($request->image){

            $imagePath = 'images/users/';
            $random_name      = Str::random(8);
            $name = $request->name.'_'. $random_name.time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(300, 200)
            ->save($imagePath.'small/'.$name);
            $data->image     = $name; 
        }

        $data->owner_login            = $request->owner_login;
        $data->cv_code_owner          = implode(',', $request->cv_code_owner);
        $data->user_type              = 'wn';
        $data->name                   = $request->name;
        $data->personal_contact       = $request->owner_login;
        $data->password               = $request->password;
        $data->status                 = 1;
        $data->status_by              = Auth::user()->id;
        $success                      = $data->save();

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
            'owner_login'           => 'required|string|max:11|unique:users,owner_login,'.$id,
            'name'                  => 'required|string|max:100',
            'cv_code_owner'         => 'required',
            'password'              => 'nullable|string|max:20',
            'image'                 => 'nullable',
        ]);

        $data = User::find($id);

      
        if( $request->image != $data->image ){

            $imagePath = 'images/users/';

            // Delete Image
            $imgDB = $data->image;
            //return $imgDB;
            if(!empty($imgDB)){
                //Delete Old File
                if (file_exists($imagePath . $imgDB)){
                    unlink( $imagePath . $imgDB );
                }
                if (file_exists($imagePath . 'small/' . $imgDB)){
                    unlink( $imagePath . 'small/' . $imgDB );
                }
            }
            
            $random_name      = Str::random(8);
            $name = $random_name. time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            //->save(public_path($imagePath).$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(300, 200)
            ->save($imagePath.'small/'.$name);

            $data->image     = $name;
            
        }

        $data->owner_login            = $request->owner_login;
        $data->cv_code_owner          = implode(',', $request->cv_code_owner);
        $data->name                   = $request->name;
        $data->personal_contact       = $request->personal_contact;
        $data->status_by              = Auth::user()->id;

        if($request->password){
            $data->password           = $request->password;
        }
        $success        = $data->save();

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
        $data       =  User::find($id);

        if($data->image){
            $imagePath = 'images/admin/';

            // Delete Image
            $imgDB = $data->image;
            //return $imgDB;
            if(!empty($imgDB)){
                //Delete Old File
                if (file_exists($imagePath . $imgDB)){
                    unlink( $imagePath . $imgDB );
                }
                if (file_exists($imagePath . 'small/' . $imgDB)){
                    unlink( $imagePath . 'small/' . $imgDB );
                }
            }
        }

        $success    =  $data->delete();

        return response()->json('success', 200);
      
    }

    // destroyTemp
    public function delete_temp($id){


        $data               = User::findOrFail($id);
        $data->deleted_temp  = 1;
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

        $data       =  User::find($id);
        if($data){

           $status = $data->status;
           
            if($status == 1){
                $data->status = null;
            }else{
                $data->status = 1;
            }
            $data->status_by              = Auth::user()->id;
            $success    =  $data->save();
            return response()->json('success', 200);

        }

    }



    // roles_data
    public function roles_data(){
        $allData = Role::orderBy('name')->get();
        return response()->json($allData, 200);
    }


    // roles_update
    public function roles_update(Request $request ){

        $id = $request->currentRoleId;

        if($id){
            $user = User::find($id);
            $success = $user->roles()->sync($request->roles);

            if($success){
                return response()->json(['msg'=>'Update Successfully &#128513;', 'icon'=>'success'], 200);
            }else{
                return response()->json([
                    'msg' => 'Data not save in DB !!'
                ], 422);
            }

        }else{
            return response()->json([
                'msg' => 'User id not found!!'
            ], 422);
        }

        
    }


    // get cv code
    public function get_cvcode($id){

        $data = User::find($id);

        // get current user cv code

        $currentCvCode = explode(",", $data->cv_code_owner);
     

        
        $allCvCodeData = self::notAssignCvCode($currentCvCode);

        $allOutletsOptions = null;

        foreach ($allCvCodeData as $key => $element) {
            

            $allOutletsOptions[] = [
                'text' => "CV: " .
                  $element->cv_code .
                  " -- " .
                  $element->shop_name .
                  " -- " .
                  $element->shop_address,

                'value' => $element->cv_code

            ];
        }



        return response()->json($allOutletsOptions, 200);
    }


    public function notAssignCvCode($currentCvCode){

        $allAssignCvCode = self::allAssignCvCode();

        // get all cv code which are not in user table cv code 
        $data = Outlet::where('status', 1)->whereNotIn('cv_code', $allAssignCvCode)->select('cv_code')->get();

        // store only cv code 
        $notAssignCvCode = [];
        foreach ($data as $item) {
            array_push($notAssignCvCode, $item->cv_code );
        }

        // merge all not assign cv code with current user already assign cv code (if any)
        $allCvCode = array_merge($notAssignCvCode, $currentCvCode);

        // get the proper array data for cv code
        $allData = Outlet::whereIn('cv_code', $allCvCode)->get();

        return $allData;
    }

    public function allAssignCvCode(){

        $data = User::get();

        // get all c code in user table
        $allAssignCvCode = [];
        foreach ($data as $value) {
            if( !empty($value->cv_code_owner) ){
                $singleData = explode(",", $value->cv_code_owner);
                foreach($singleData as $item2){
                    array_push($allAssignCvCode, $item2);
                }
            }
            
        }

        return $allAssignCvCode;


    }


    public function not_assign_outlets(){
        // all user table assign cv code
        $allAssignCvCode = self::allAssignCvCode();

        // get all cv code which are not in user table cv code 
        $allData = Outlet::where('status', 1)->whereNotIn('cv_code', $allAssignCvCode)->select('cv_code', 'shop_name', 'shop_address', 'id')->get();


        $allOutletsOptions = null;

        foreach ($allData as $key => $element) {
            

            $allOutletsOptions[] = [
                'text' => "CV: " .
                  $element->cv_code .
                  " -- " .
                  $element->shop_name .
                  " -- " .
                  $element->shop_address,

                'value' => $element->cv_code

            ];
        }

        //dd($allOutletsOptions);
        

        return response()->json($allOutletsOptions, 200);





    }

    

    
}

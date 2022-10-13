<?php

namespace App\Http\Controllers\Food\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Food\Role;
use App\Models\Food\UserRole;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use App\Models\Food\Outlet;

class IndexController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');

        $userTblQuery = User::query()->whereNull('deleted_temp')->whereNotNull('login');

        $allData = (clone $userTblQuery)->with('roles', 'zones') 
            ->orderBy($sort_field, $sort_direction)
            ->search( trim(preg_replace('/\s+/' ,' ', $search)) )
            ->paginate($paginate);

       
        // Append Filed Data
        foreach($allData as $item){
            $manager_details = null;
            $makby = null;
            $online = false;
           
            // manager_details
            if($item->manager_id){
                $manager_details = User::where('id', $item->manager_id)->select('name')->first();
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
            $item->manager_details =  $manager_details;
            $item->makby  =  $makby;
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
    

    // managers 
    public function managers(){
        $allData = User::whereNotNull('login')->where('officer_type', 'mg')
        ->where('status', 1)
        ->orderBy('name')
        ->select('id as value', 'name as text')
        ->get()
        ->toArray();
        return response()->json($allData, 200);
    }


    // store
    public function store(Request $request){

        //Validate
        $this->validate($request,[
            'login'         => 'required|string|max:100|unique:users',
            'name'          => 'required|string|max:100',
            'officer_type'  => 'nullable|string|max:100',
            'image'         => 'required',

        ]);

        $data = new User();

      
        if($request->image){

            $imagePath        = 'images/users/';
            $random_name      = Str::random(8);
            $name = $random_name.time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(300, 200)
            ->save($imagePath.'small/'.$name);

            $data->image     = $name;
            
        }

        


        $data->login         = $request->login;
        $data->name          = $request->name;
        $data->officer_type  = $request->officer_type;
        $data->manager_id    = $request->manager_id;
        
        $data->user_type     = 'ad';
        $data->status        = 1;
        $data->status_by     = Auth::user()->id;
        $success             = $data->save();

       
        // Zone Sync
        $data->zones()->sync($request->zone_id);

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
            'login'         => 'required|string|max:100|unique:users,login,'.$id,
            'name'          => 'required|string|max:100',
            'officer_type'  => 'nullable|string|max:100',
            'image'         => 'nullable',
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

        // Zone Sync
        $data->zones()->sync($request->zone_id);

       

        $data->login         = $request->login;
        $data->name          = $request->name;
        $data->officer_type  = $request->officer_type;
        $data->manager_id    = $request->manager_id;

        $data->user_type     = 'ad';
        $data->status_by     = Auth::user()->id;
        $success             = $data->save();


        if($success){
            return response()->json(['msg'=>'Updated Successfully &#128515;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }


    // // destroy
    // public function destroy($id)
    // {
    //     $data       =  User::find($id);

    //     if($data->image){
    //         $imagePath = 'images/admin/';

    //         // Delete Image
    //         $imgDB = $data->image;
    //         //return $imgDB;
    //         if(!empty($imgDB)){
    //             //Delete Old File
    //             if (file_exists($imagePath . $imgDB)){
    //                 unlink( $imagePath . $imgDB );
    //             }
    //             if (file_exists($imagePath . 'small/' . $imgDB)){
    //                 unlink( $imagePath . 'small/' . $imgDB );
    //             }
    //         }
    //     }

    //     $success    =  $data->delete();

    //     return response()->json('success', 200);
      
    // }

    // destroyTemp
    public function delete_temp($id){


        $data                = User::findOrFail($id);
        $data->deleted_temp  = 1;
        $data->deleted_by    = Auth::user()->id;
        $success             = $data->save();

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
            $data->status_by  = Auth::user()->id;
            $success          = $data->save();
            return response()->json('success', 200);

        }

    }



    // roles_data
    public function roles_data(){

        if(Auth::user()->hasRole('Administrator')){
            $allData = Role::select('id','name')->orderBy('name')->get();
        }else{
            $allData = Role::whereNotIn('name', ['Administrator'])->select('id','name')->orderBy('name')->get();
        }

        foreach ($allData as $key => $value) {
            if (($pos = strpos($value->name, "-")) !== FALSE) { 
                $string = substr($value->name, $pos+1); 
                $value->only_text =  $string;
                $value->key_name =  explode("-", $value->name )[0];
            }else{
                $value->only_text = $value->name;
                $value->key_name  = $value->name;
            }
        }

        $final = $allData->groupBy('key_name');

        return response()->json($final, 200);

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


    // zones_update
    public function zones_update(Request $request ){

        $id = $request->currentId;

        if($id){
            $user = User::find($id);
            $success = $user->zones()->sync($request->selectedData);

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
    
}

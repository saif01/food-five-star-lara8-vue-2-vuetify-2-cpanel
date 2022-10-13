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

class OperatorController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $search_field   = Request('search_field', '');

        $userTblQuery = User::query()->whereNull('deleted_temp')->whereNotNull('cv_code');

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
            $makby = null;
            $online = false;
            
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
            'cv_code'               => 'required|string|max:100|unique:users',
            'name'                  => 'required|string|max:100',
            'personal_contact'      => 'required|string|max:15',
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


        $data->cv_code                = $request->cv_code;
        $data->user_type              = 'op';
        $data->name                   = $request->name;
        $data->personal_contact       = $request->personal_contact;
        $data->password               = $request->password;
        $data->status                 = 1;
        $data->status_by              =  Auth::user()->id;
        $success                      =  $data->save();

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
            'cv_code'               => 'required|string|max:100|unique:users,cv_code,'.$id,
            'name'                  => 'required|string|max:100',
            'personal_contact'      => 'required|string|max:15',
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


        $data->cv_code                = $request->cv_code;
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


        $data                = User::findOrFail($id);
        $data->deleted_temp  = 1;
        $data->deleted_by    = Auth::user()->id;
        $data->updated_at    = Carbon::now();
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

    
}

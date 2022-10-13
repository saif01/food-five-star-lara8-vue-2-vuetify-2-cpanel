<?php

namespace App\Http\Controllers\Food\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\Zone;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class ZoneController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');

        $allData = Zone::with('makby:id,name')->orderBy($sort_field, $sort_direction)
                ->whereNull('deleted_temp')
                // ->whereNull('status')
                ->search( trim(preg_replace('/\s+/' ,' ', $search)) )
                ->paginate($paginate);

        return response()->json($allData, 200);

    }


    // store
    public function store(Request $request){

        //Validate
        $this->validate($request,[
            'name'         => 'required|string|max:100|unique:zones',
        ]);

        $data = new Zone();


        $data->name         = $request->name;
        $data->status       = 1;
        $data->created_by   =  Auth::user()->id;
        $success            = $data->save();

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
            'name'         => 'required|string|max:100|unique:zones,name,'.$id,
        ]);

        $data = Zone::find($id);

      
        $data->name         = $request->name;
        $data->created_by   =  Auth::user()->id;
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
        $data       =  Zone::find($id);
        $success    =  $data->delete();

        return response()->json('success', 200);
      
    }

    // destroyTemp
    public function delete_temp($id){


        $data                = Zone::findOrFail($id);
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

        $data       =  Zone::find($id);
        if($data){

           $status = $data->status;
           
            if($status == 1){
                $data->status = null;
            }else{
                $data->status = 1;
            }
            $data->created_by   =  Auth::user()->id;
            $success    =  $data->save();
            return response()->json('success', 200);

        }

    }

    
}

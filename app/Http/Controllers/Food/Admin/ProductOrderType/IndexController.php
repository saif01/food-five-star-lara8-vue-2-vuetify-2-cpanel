<?php

namespace App\Http\Controllers\Food\Admin\ProductOrderType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\ProductOrderType;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class IndexController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');

        $allData = ProductOrderType::with('makby:id,name')->orderBy($sort_field, $sort_direction)
                ->whereNull('deleted_temp')
                ->search( trim(preg_replace('/\s+/' ,' ', $search)) )
                ->paginate($paginate);

        return response()->json($allData, 200);

    }


    // store
    public function store(Request $request){

        //Validate
        $this->validate($request,[
            'name_en'       => 'required|string|max:100',
            'name_bn'       => 'required|string|max:100',
        ]);

        $data = new ProductOrderType();


        $data->name_en      = $request->name_en;
        $data->name_bn      = $request->name_bn;
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
            'name_en'       => 'required|string|max:100',
            'name_bn'       => 'required|string|max:100',
        ]);

        $data = ProductOrderType::find($id);


        $data->name_en      = $request->name_en;
        $data->name_bn      = $request->name_bn;
        $data->created_by   =  Auth::user()->id;
        $success            = $data->save();

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
        $data       =  ProductOrderType::find($id);

        $success    =  $data->delete();

        return response()->json('success', 200);
      
    }

    // delete_temp
    public function delete_temp($id){


        $data                   = ProductOrderType::findOrFail($id);
        $data->deleted_temp     = 1;
        $data->deleted_by        = Auth::user()->id;
        $data->updated_at       = Carbon::now();
        $success                = $data->save();

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

        $data       =  ProductOrderType::find($id);
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
}

<?php

namespace App\Http\Controllers\Food\Admin\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Food\MessageTemplate;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');

        $allData = MessageTemplate::with('makby:id,name')->orderBy($sort_field, $sort_direction)
                // ->whereNull('status')
                ->whereNull('deleted_temp')
                ->search( trim(preg_replace('/\s+/' ,' ', $search)) )
                ->paginate($paginate);

        return response()->json($allData, 200);

    }


    // store
    public function store(Request $request){

        //Validate
        $this->validate($request,[
            'title'         => 'required|unique:message_templates',
            'message'       => 'required',
        ]);

        

        $data = new MessageTemplate();

        $data->title        = Str::slug($request->title);
        $data->message      = $request->message;
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
            'title'         => 'required|unique:message_templates,title,'.$id,
            'message'       => 'required',
        ]);

        $data = MessageTemplate::find($id);

      
        $data->title        = Str::slug($request->title);
        $data->message      = $request->message;
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
        $data       =  MessageTemplate::find($id);
        $success    =  $data->delete();

        return response()->json('success', 200);
      
    }

    // delete_temp
    public function delete_temp($id){


        $data                = MessageTemplate::findOrFail($id);
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

        $data       =  MessageTemplate::find($id);
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

<?php

namespace App\Http\Controllers\Food\Admin\Announcement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\User;
use App\Models\Food\Announcement;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');

        $allData = Announcement::with('makby:id,name')
            ->whereNull('deleted_temp')
            ->orderBy($sort_field, $sort_direction)
            ->search( trim(preg_replace('/\s+/' ,' ', $search)) )
            ->paginate($paginate);

        return response()->json($allData, 200);

    }

    // store
    public function store(Request $request){


        //Validate
        $this->validate($request,[
            'message'      => 'nullable',
            'start'   => 'required',
            'end'     => 'required',
            'allow'     => 'required',
        ]);

        $data = new Announcement();


        if($request->image){

            $imagePath = 'images/announcement/';
            $random_name      = \Str::random(8);
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

    
        $data->message      = $request->message;
        $data->start   = $request->start;
        $data->end     = $request->end;
        $data->allow     = $request->allow;
        
        $data->status       = 1;
        $data->created_by   = Auth::user()->id;
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
            'message'      => 'nullable',
            'start'   => 'required',
            'end'     => 'required',
            'allow'     => 'required',
        ]);

        $data = Announcement::find($id);

        if( $request->image != $data->image ){

            $imagePath = 'images/announcement/';

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
            
            $random_name      = \Str::random(8);
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

        $data->message      = $request->message;
        $data->start   = $request->start;
        $data->end     = $request->end;
        $data->allow     = $request->allow;
        
        $data->status       = 1;
        $data->created_by   = Auth::user()->id;
        $success            = $data->save();

        if($success){
            return response()->json(['msg'=>'Updated Successfully &#128515;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }


    //Delete
    public function destroy($id){

        $data       = Announcement::findOrFail($id);
        $success    = $data->delete();

        if($success){
            return response()->json(['success' => 'Successfully Deleted', 'icon' => 'success']);
        }else{
            return response()->json(['success' => 'Something going wrong !!', 'icon' => 'error']);
        }
    }


    // delete_temp
    public function delete_temp($id){
        
        $data                = Announcement::findOrFail($id);
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

        $data       =  Announcement::find($id);
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

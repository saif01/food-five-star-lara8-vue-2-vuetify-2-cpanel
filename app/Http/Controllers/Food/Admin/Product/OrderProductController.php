<?php

namespace App\Http\Controllers\Food\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\ProductOrder;
use App\Models\Food\ProductOrderType;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class OrderProductController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');

        $allData = ProductOrder::with('makby:id,name','order_type')
                ->whereNull('deleted_temp')
                ->search( trim(preg_replace('/\s+/' ,' ', $search)) )
                ->orderBy($sort_field, $sort_direction)
                ->paginate($paginate);

        return response()->json($allData, 200);

    }


    // store
    public function store(Request $request){
        // dd($request->category);

        //Validate
        $this->validate($request,[
            'product_code'    => 'required|unique:product_orders',
            'type'            => 'required',
            'packaging_type'  => 'nullable',
            'type_bn'         => 'required',
            'category'        => 'required',
            'mrp'             => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
            'quantity'        => 'required|integer',
            'remark'          => 'nullable|string',
            'image'           => 'required',
        ]);

        if($request->category != '4' && $request->category != '5'){
            $this->validate($request,[
                'packaging_type'  => 'nullable',
                'tp'              => 'required|regex:/^\d+(\.\d{1,3})?$/',
                // 'gp'              => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
                // 'gp_percentage'   => 'required',
            ]);
        }

        if($request->packaging_type == 'Pack'){
            $this->validate($request,[
                'weight'          => 'required',
                'weight_type'     => 'nullable',
            ]);
        }

        $data = new ProductOrder();

        if($request->image){

            $imagePath = 'images/order/';
            $random_name      = Str::random(8);
            $req_name = Str::slug($request->type ?? 'nan');
            $name = $req_name.'_'.$random_name.time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(200, 200)
            ->save($imagePath.'small/'.$name);

            $data->image     = $name;
            
        }

        $data->product_code     = $request->product_code;
        $data->type             = $request->type;
        $data->type_bn          = $request->type_bn;
        $data->category         = $request->category;
        $data->packaging_type   = $request->packaging_type;

        // if($request->packaging_type == 'Pack'){
            $data->weight           = $request->weight;
            $data->weight_type      = $request->weight_type;
            $data->quantity         = $request->quantity;
        //}

        $data->tp               = $request->tp;
        $data->mrp              = $request->mrp;
        // $data->gp               = $request->gp;
        // $data->gp_percentage    = $request->gp_percentage;
        $data->remark           = $request->remark;

        $data->status           = 1;
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
            'product_code'    => 'required|unique:product_orders,product_code,'.$id,
            'type'            => 'required',
            'type_bn'         => 'required',
            'category'        => 'required',
            'mrp'             => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
            'quantity'        => 'required|integer',
            'remark'          => 'nullable|string',
            'image'           => 'nullable',
        ]);

        if($request->category != '4' && $request->category != '5'){
            $this->validate($request,[
                'packaging_type'  => 'required',
                'tp'              => 'required|regex:/^\d+(\.\d{1,3})?$/',
                // 'gp'              => 'required|regex:/^\d+(\.\d{1,3})?$/',
                // 'gp_percentage'   => 'required',
            ]);
        }

        if($request->packaging_type == 'Pack'){
            $this->validate($request,[
                'weight'          => 'required',
                'weight_type'     => 'nullable',
            ]);
        }

        $data = ProductOrder::find($id);

        if( $request->image != $data->image ){

            $imagePath = 'images/order/';

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
            $req_name = Str::slug($request->type ?? 'nan');
            $name = $req_name.'_'.$random_name.time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            //->save(public_path($imagePath).$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(200, 200)
            ->save($imagePath.'small/'.$name);

            $data->image     = $name;
            
        }

        $data->product_code     = $request->product_code;
        $data->type             = $request->type;
        $data->type_bn          = $request->type_bn;
        $data->category         = $request->category;
        $data->packaging_type   = $request->packaging_type;

        //if($request->packaging_type == 'Pack'){
            $data->weight           = $request->weight;
            $data->weight_type      = $request->weight_type;
            $data->quantity         = $request->quantity;
        //}
        
        $data->tp               = $request->tp;
        $data->mrp              = $request->mrp;
        // $data->gp               = $request->gp;
        // $data->gp_percentage    = $request->gp_percentage;
        $data->remark           = $request->remark;

        $data->status           = 1;
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
        $data       =  ProductOrder::find($id);

        if($data->image){
            $imagePath = 'images/product/';

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

    // delete_temp
    public function delete_temp($id){


        $data                = ProductOrder::findOrFail($id);
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

        $data       =  ProductOrder::find($id);
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


    public function type(){
        $allData = ProductOrderType::where('status', 1)->get();
        return response()->json($allData, 200);
    }

}

<?php

namespace App\Http\Controllers\Food\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Food\ProductSale;
use App\Models\Food\ProductType;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use App\Models\Food\ProductOrder;

class SaleProductController extends Controller
{
    //index
    public function index(){

        $paginate       = Request('paginate', 10);
        $search         = Request('search', '');
        $sort_direction = Request('sort_direction', 'desc');
        $sort_field     = Request('sort_field', 'id');
        $sort_direction_custom = Request('sort_direction_custom', null);
        $sort_field_custom     = Request('sort_field_custom', null);

        $allDataQuery = ProductSale::with('type', 'makby:id,name', 'sales_product', 'free')
                ->whereNull('deleted_temp');

        // if($sort_field_custom){

        //     if($sort_field_custom == 'free_item'){

        //         //dd($sort_field_custom, $sort_direction_custom );

        //         if($sort_direction_custom == 'asc'){
        //             $allDataQuery->whereNull('free_item');
        //         }else{
        //             $allDataQuery->whereNotNull('free_item');
        //             //->orderBy('free_item', 'desc');
        //         }
        //     }
            
        // }else{
            $allDataQuery->orderBy($sort_field, $sort_direction);
        // }


        $allData = $allDataQuery->search( trim(preg_replace('/\s+/' ,' ', $search)) )
            ->paginate($paginate);

        return response()->json($allData, 200);

    }


    // store
    public function store(Request $request){

        //Validate
        $this->validate($request,[
            'name_en'               => 'required|string|max:100',
            'name_bn'               => 'required|string|max:100',
            'type_id'               => 'nullable|max:100',
            'product_order_id'      => 'nullable',
            'price'                 => 'required',
            'price_offer'           => 'nullable',
            'image'                 => 'required',
        ]);

        $data = new ProductSale();

      
        if($request->image){

            $imagePath = 'images/product/';
            $random_name      = Str::random(8);
            $req_name = Str::slug($request->name_en ?? 'nan');
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


        $data->name_en              = $request->name_en;
        $data->name_bn              = $request->name_bn;
        $data->type_id              = $request->type_id;
        $data->product_order_id     = $request->product_order_id;
        $data->price                = $request->price;
        $data->price_offer          = $request->price_offer;
        $data->status               = 1;
        $data->created_by           =  Auth::user()->id;
        $success                    = $data->save();

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
            'name_en'               => 'required|string|max:100',
            'name_bn'               => 'required|string|max:100',
            'type_id'               => 'nullable|max:100',
            'product_order_id'      => 'nullable',
            'price'                 => 'required',
            'price_offer'           => 'nullable',
            'image'                 => 'nullable',
        ]);

        $data = ProductSale::find($id);

        if( $request->image != $data->image ){

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
            
            $random_name      = Str::random(8);
            $req_name = Str::slug($request->name_en ?? 'nan');
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


        $data->name_en              = $request->name_en;
        $data->name_bn              = $request->name_bn;
        $data->type_id              = $request->type_id;
        $data->product_order_id     = $request->product_order_id;
        $data->price                = $request->price;
        $data->price_offer          = $request->price_offer;
        $data->status               = 1;
        $data->created_by           = Auth::user()->id;
        $success                    = $data->save();

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
        $data       =  ProductSale::find($id);

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


        $data                = ProductSale::findOrFail($id);
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

        $data       =  ProductSale::find($id);
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
        $allData = ProductType::where('status', 1)->get();
        return response()->json($allData, 200);
    }


    public function sales_product(){

        $allData = ProductOrder::select('id','type', 'product_code')->get()->toArray();

        return response()->json($allData, 200);

    }

    public function free_item(){

        $allData = ProductSale::with('type')->select('id', 'name_en', 'type_id')->get();

        return response()->json($allData, 200);

    }
}

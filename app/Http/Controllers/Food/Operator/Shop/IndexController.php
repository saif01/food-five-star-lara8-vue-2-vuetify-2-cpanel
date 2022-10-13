<?php

namespace App\Http\Controllers\Food\Operator\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\Outlet;
use App\Models\Food\UserLanguage;
use Auth;
use App\Models\User;

class IndexController extends Controller
{
    //index
    public function index(){
       return Outlet::
        with([ 'user'=>function($q2){
            $q2->select('id', 'image', 'cv_code', 'name');
        } ,'user.language'=>function($q){
            $q->select('id', 'user_id', 'language');
        }])
       ->select('id', 'cv_code', 'shop_name', 'shop_address', 'owner_mobile', 'city', 'district', 'division')
       ->where('cv_code', Auth::user()->cv_code)
       ->first();
    }


    
    // update
    public function update(Request $request){

        //Validate
        $this->validate($request,[
            'language'         => 'nullable',
        ]);

        $data = Outlet::find($request->id);

        $data->created_by       = Auth::user()->id;

        // update language in user language table
        $allData = UserLanguage::where('user_id',Auth::user()->id)->first();
        $allData->language        = $request->language;


        $userData = User::find(Auth::user()->id);

        if( $request->image != $userData->image ){

            $imagePath = 'images/users/';

            // Delete Image
            $imgDB = $userData->image;
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

            $userData->image     = $name;
            
        }

        $success                  = $userData->save();
        $success                  = $allData->save();

        if($success){
            return response()->json(['msg'=>'Updated Successfully &#128515;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }
    
}

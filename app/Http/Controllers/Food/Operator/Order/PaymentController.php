<?php

namespace App\Http\Controllers\Food\Operator\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\OrderSummary;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // store
    public function store(Request $request){

        //Validate
        $this->validate($request,[
            'payment_amount'      => 'required',
            'image'               => 'required',
        ]);
        

        $data = OrderSummary::where('order_number', $request->order_number)->first();

      
        if($request->image){

            $imagePath = 'images/order/';
            $random_name      = \Str::random(8);
            $name = $random_name.time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(300, 200)
            ->save($imagePath.'small/'.$name);

            $data->payment_doc     = $name;
            
        }


        $data->payment_amount   = $request->payment_amount;
        $data->payment_prove    = Carbon::now()->toDateTimeString();
        $success                = $data->save();

        if($success){
            return response()->json(['msg'=>'Stored Successfully &#128513;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }
}

<?php

namespace App\Http\Controllers\Food\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food\OrderSummary;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Food\Message\IndexController;
use App\Models\Food\MessageTemplate;
use App\Models\Food\Outlet;

class ApprovalController extends Controller
{

    // manager
    public function manager(Request $request){

        //Validate
        $this->validate($request,[
            'order_number'        => 'required',
            'payment_amount'      => 'nullable',
            'image'               => 'nullable',
        ]);
        
        $order_number = $request->order_number;

        

        $data = OrderSummary::where('order_number', $order_number)->first();
        if(!$data){
             return response()->json(['msg'=>'Order Not Found !!', 'icon'=>'warning'], 200);
        }
        //dd($data);
      
        if($data && $request->image != $data->payment_doc){

            $imagePath = 'images/order/';
            // $random_name      = \Str::random(8);
            $name = $order_number.'_'.time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(300, 200)
            ->save($imagePath.'small/'.$name);

            $data->payment_doc     = $name;
        }

        if($request->payment_amount){
            $data->payment_amount   = $request->payment_amount;
            $data->payment_prove    = Carbon::now()->toDateTimeString();
        }
        
        $data->manager_id           = Auth::user()->id;
        $data->manager_approve      = Carbon::now()->toDateTimeString();

        $success                    = $data->save();

        if($success){
            return response()->json(['msg'=>'Manager Approved Successfully &#128513;', 'icon'=>'success'], 200);
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }

    // admin
    public function admin(Request $request){

        //Validate
        $this->validate($request,[
            'order_number'        => 'required',
            'payment_amount'      => 'nullable',
            'image'               => 'nullable',
        ]);
        
        $order_number = $request->order_number;

        $data = OrderSummary::where('order_number', $order_number)->first();
        if(!$data){
            return response()->json(['msg'=>'Order Not Found !!', 'icon'=>'warning'], 200);
        }
      
        if($request->image != $data->payment_doc){

            $imagePath = 'images/order/';
            // $random_name      = \Str::random(8);
            $name = $order_number.'_'.time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            // Original Image Save
            \Image::make($request->image)
            ->save($imagePath.$name);
            // Resized image save
            \Image::make($request->image)
            ->resize(300, 200)
            ->save($imagePath.'small/'.$name);

            $data->payment_doc     = $name; 
        }


        
        if($request->payment_amount){
            $data->payment_amount   = $request->payment_amount;
            $data->payment_prove    = Carbon::now()->toDateTimeString();
        }
        
        $data->admin_id            = Auth::user()->id;
        $data->admin_approve       = Carbon::now()->toDateTimeString();
        $success                   = $data->save();
        // $success                   = true;

        if($success){
            // send confirm message
            self::sentConfirmMessage($data->total_price, $data->cv_code);
            
            return response()->json(['msg'=>'Admin Approved Successfully &#128513;', 'icon'=>'success'], 200);
            
        }else{
            return response()->json([
                'msg' => 'Data not save in DB !!'
            ], 422);
        }

    }

    // Send Order Conformation SMS
    public function sentConfirmMessage($amount, $cv_code){
        $msgData = MessageTemplate::where('status', 1)->where('title', 'order-confirmation')->select('message')->first();

        if($msgData){
            $outlet = Outlet::where('status', 1)->where('cv_code', $cv_code)->select('shop_name', 'owner_mobile', 'owner_mobile_2')->first();

            if($msgData->message){
                if(strstr( $msgData->message, '{outlet_name}' ) && strstr( $msgData->message, '{date}' ) && strstr( $msgData->message, '{amount}' ) ){

                    $replaceOutletName = str_replace("{outlet_name}","$outlet->shop_name",$msgData->message);

                    $replaceDate = str_replace("{date}",Carbon::now()->format('j-M-y'),$replaceOutletName);

                    $replaceAmount = str_replace("{amount}",$amount,$replaceDate);

                    $msg = $replaceAmount;

                }else{
                    $msg = 'Dear Sir, The order for "'.$outlet->shop_name.'" has been finalized on '.Carbon::now()->format('j-M-y').'. Your total amount is '.$amount.' Taka. Thank you.';
                }

            }else{
                $msg = 'Dear Sir, The order for "'.$outlet->shop_name.'" has been finalized on '.Carbon::now()->format('j-M-y').'. Your total amount is '.$amount.' Taka. Thank you.'; 
            }
            

            // $res = IndexController::SentOrderConfirmMessage($outlet->owner_mobile ?? $outlet->owner_mobile_2,$msg);

            $res = IndexController::SentOrderConfirmMessage('01760430610',$msg);
        }else{
            return true;
        }
        
        
    }
}

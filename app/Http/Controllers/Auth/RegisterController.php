<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserReginster;
use Auth;
use App\Http\Controllers\Common\ImageUpload;
use App\Http\Controllers\Auth\Common\ADLogin;

class RegisterController extends Controller
{
    // Image Uplaod
    use ImageUpload;

    //check
    public function check(Request $request){

        // Validations
        request()->validate([
            'login' => 'required|max:20',
            'password' => 'required|max:20',
        ]);

        $login      = $request->login;
        $password   = $request->password;

        // Check Local Server User tbl Data
        $localServerData = User::where('login', $login)->count();
        // Check Local Server UserRegister tbl Data
        $localServerRegisterData = UserReginster::where('login', $login)->first();

        //$localServerData = 0;

        if($localServerData > 0){

            // Data found in local server User tbl
            $response = (object) [
                'code'      => '202',
                'status'    => 'success',
                'icon'      => 'info',
                'msg'       => $login.' are already registered',
                'data'      => '',
            ];
        }
        elseif($localServerRegisterData){

            if( $localServerRegisterData->verify == 1 ){
                // Data found in local server UserRegister tbl Approved
                $response = (object) [
                    'code'      => '208',
                    'status'    => 'success',
                    'icon'      => 'success',
                    'msg'       => $login.' Can Login Now, Approved already',
                    'data'      => '',
                ];
            }else{
                // Data found in local server UserRegister tbl  Not Approved
                $response = (object) [
                    'code'      => '203',
                    'status'    => 'success',
                    'icon'      => 'info',
                    'msg'       => 'Waiting for Approval<br>'.$login.' are already registered',
                    'data'      => '',
                ];
            }
        }
        else{
            // Data not found in local server 
            // Login Check By AD
            $adResponse = ADLogin::Data($login, $password);

            if($adResponse->status == 'success'){

                $response = (object) [
                    'code'      => '200',
                    'status'    => 'success',
                    'icon'      => 'success',
                    'msg'       => 'Data found in AD',
                    'data'      => $adResponse,
                ];

            }else{

                $response = (object) [
                    'code'      => '204',
                    'status'    => 'error',
                    'icon'      => 'error',
                    'msg'       => $login.' not found in server, Pleaee contact with IT',
                    'data'      => '',
                ];
            }


        }
        
        
        
        // dd($adResponse);

        return $response;



    }

    // store
    public function store(Request $request){

       
        //dd($request->all());

        //Validate
        $this->validate($request,[
            'login'             => 'required|unique:user_reginsters|max:255',
            'office_contact'    => 'nullable|min:11|max:15',
            'personal_contact'  => 'nullable|min:11|max:15',
            // 'office_contact'    => 'nullable|regex:/(01)[0-9]{9}/|max:15',
            // 'personal_contact'  => 'nullable|regex:/(01)[0-9]{9}/|max:15',
            'personal_email'    => 'required|email',
            'office_email'      => 'nullable|email',
            'office'            => 'nullable',
            'business_unit'     => 'nullable',
            'nid'               => 'nullable|regex:/[0-9]/',
            'manager_name'      => 'required',
            'bu_name'           => 'required',
            'manager_email'     => 'required|email',
            'bu_email'          => 'required|email',
        ]);

    
        $login            = strtolower($request->login);
        $name             = $request->name;
        $office_email     = $request->office_email;
        $personal_email   = $request->personal_email;

        $data = new UserReginster();

        $data->login            = $login;
        $data->name             = $name;
        $data->department       = $request->department;
        $data->office_id        = $request->office_id;
        $data->office_contact   = $request->office_contact;
        $data->personal_contact = $request->personal_contact;
        $data->office_email     = $office_email;
        $data->personal_email   = $personal_email;
        $data->office           = $request->office;
        $data->business_unit    = $request->business_unit;
        $data->nid              = $request->nid;

        $data->manager_name     = $request->manager_name;
        $data->manager_email    = $request->manager_email;
        $data->bu_name          = $request->bu_name;
        $data->bu_email         = $request->bu_email;
        $data->remarks          = $request->remarks;
        
        $imagePath      = 'images/users/';
        $current_image  = $request->image; 
        // Save Image a
        if(!empty($current_image)){
            $imgName= $this->imageUplaodByName($current_image, null, $imagePath); 
            $data->image = $imgName;
        }
    
        $data->status           = 0;
        $data->verify           = 0;
    
        $success                = $data->save();

        if($success){

             // Data found in local server 
            $response = (object) [
                'code'      => '200',
                'status'    => 'success',
                'icon'      => 'success',
                'msg'       => 'Registered Successfully, Please check your Email',
                'data'      => '',
            ];

        }else{
             // Data found in local server 
            $response = (object) [
                'code'      => '202',
                'status'    => 'error',
                'icon'      => 'error',
                'msg'       => 'Somthing going wrong! Please, try again',
                'data'      => '',
            ];
        }


        

        return $response;

        // if($success){
        //     return response()->json(['msg'=>'Stored Successfully &#128513;', 'icon'=>'success'], 200);
        // }else{
        //     return response()->json([
        //         'msg' => 'Data not save in DB !!'
        //     ], 422);
        // }

    }


}

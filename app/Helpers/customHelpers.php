<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use App\Models\User;


// define('ADMIN', '1');
// define('GU', '2');

define('ADD', 'can_add');
define('EDIT', 'can_edit');
define('VIEW', 'can_view');
define('DELETE', 'can_delete');


function isOperatorType(){

    dd(Auth::user());

    $result = DB::table('users')->where('id', Auth::user('id'))->where('user_type', 'op')->first();
    dd($result);
    if($this->where('id', Auth::user()->id)->where('user_type', 'op')->first()){
        return true;
    }
    return false;
}

// //Auth support function
// function logged_in_user_id(){

//     $logged_in_id = '';
//     $logged_in_id = Auth::user()->id;
//     return $logged_in_id;
// }

// function logged_in_user_name(){

//     $logged_in_name = '';
//     $logged_in_name = Auth::user()->name;
//     return $logged_in_name;
// }

// function logged_in_role_id(){

//     $logged_in_role_id = 0;
//     if (Auth::user()->role_id) :
//         $logged_in_role_id = Auth::user()->role_id;
//     endif;
//     return $logged_in_role_id;
// }

// function logged_in_role_name() {
//     $logged_in_role_name = '';
//    if (Auth::user()->id && Auth::user()->role->id):
//         $logged_in_role_name=Auth::user()->role->role_name;
//     endif;
//     return $logged_in_role_name;
// }

// function is_super_admin() {
//     if(logged_in_role_name() === 'Super Admin'):
//         return true;
//     else:
//         return false;
//     endif;
// }

// //Role & permission support function
// function hasPermission($module,$permission) {
//     $module=trim($module);
//     $role_id=Auth::user()->role->id;
//     $getrole=\App\Role::find($role_id);
//     if($getrole->role_name=="Super Admin")
//     {
//         return true;
//     }
//     $count = DB::table('permission_categories as PC')
//         ->leftJoin('role_permissions as RP', 'PC.id', '=', 'RP.permission_category_id')
//         ->where('PC.short_code', '=',$module)
//         ->where('RP.role_id', '=',$role_id)
//         ->where('RP.'.$permission, '=',1)
//         ->select('PC.short_code', 'RP.*')
//         ->get()->count();
//     if($count>0){
//         return true;
//     }else{
//         return false;
//     }
// }

// function checkPermission($module,$permission)
// {
//     if(!hasPermission($module,$permission))
//     {
//         setMessage("msg","warning","You are unauthorized for this action!");
//         return redirect(route('dashboard'))->send();
//         exit;
//     }
// }


// //for sidebar
// function hasActive($module) {
//     $module=trim($module);
//     $role_id=Auth::user()->role_id;
//     $getrole=\App\Role::find($role_id);
//     if($getrole->name=="Super Admin")
//     {
//         return true;
//     }
//     $count=DB::table("permission_groups")
//         ->where("short_code","=",$module)
//         ->where("is_active","=",1)
//         ->get()->first();
//     if(isset($count)){
//         return true;
//     }
//     else{
//         return false;
//     }
// }

// //Session Message Support Function
// function setMessage($key, $class, $message){
//     session()->flash($key, $message);
//     session()->flash("class", $class);
//     return true;
// }
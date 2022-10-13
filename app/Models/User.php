<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Food\Outlet;
use Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** 
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function orders(){
    //     return $this->hasMany('App/Food/Order');
    // }

    //protected $appends = ['cv_code_array', 'cv_code_outlets', 'manager_details'];

    // public function getCvCodeArrayAttribute()
    // {
    //     if($this->cv_code_owner){
    //         return explode(',', $this->cv_code_owner);
    //     }else{
    //         return [];
    //     }
    // }

    // public function getCvCodeOutletsAttribute()
    // {
    //     if($this->cv_code_owner){
    //         $cvArr = explode(',', $this->cv_code_owner);
    //         $res = [];
    //         if(!empty($cvArr)){
    //             foreach($cvArr as $cv_code){
    //                 $res[] = Outlet::where('cv_code', $cv_code)->first();
    //             }
    //         }
    //         return $res;
    //     }else{
    //         return [];
    //     }
        
    // }

    // public function getManagerDetailsAttribute()
    // {
    //     if($this->manager_id){
    //         return User::where('id', $this->manager_id)->select('name')->first();
    //     }else{
    //         return null;
    //     }
    // }


    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('login', 'LIKE', '%'.$val.'%')
        ->Orwhere('cv_code', 'LIKE', '%'.$val.'%')
        ->Orwhere('owner_login', 'LIKE', '%'.$val.'%')
        ->Orwhere('name', 'LIKE', '%'.$val.'%')
        ->Orwhere('department', 'LIKE', '%'.$val.'%')
        ->Orwhere('office_id', 'LIKE', '%'.$val.'%')
        ->Orwhere('office_contact', 'LIKE', '%'.$val.'%')
        ->Orwhere('personal_contact', 'LIKE', '%'.$val.'%')
        ->Orwhere('office_email', 'LIKE', '%'.$val.'%')
        ->Orwhere('personal_email', 'LIKE', '%'.$val.'%')
        ->Orwhere('office', 'LIKE', '%'.$val.'%')
        ->Orwhere('business_unit', 'LIKE', '%'.$val.'%')
        ->Orwhere('nid', 'LIKE', '%'.$val.'%'); 
    }

    // // zone
    // public function zone(){
    //     return $this->hasOne('App\Models\Food\Zone', 'id', 'zone_id');
    // }



    // check hasOperator access
    public function hasOperator(){
        if($this->where('id', Auth::user()->id)->where('user_type', 'op')->count()){
            return true;
        }
        return false;
    }

    // check hasOwner access
    public function hasOwner(){
        if($this->where('id', Auth::user()->id)->where('user_type', 'wn')->count()){
            return true;
        }
        return false;
    }

    // check admin access
    public function hasAdmin(){
        if($this->where('id', Auth::user()->id)->where('user_type', 'ad')->count()){
            return true;
        }
        return false;
    }

    // zones
    public function zones(){
        return $this->belongsToMany('App\Models\Food\Zone', 'user_zones', 'user_id', 'zone_id')->where('status', 1);
    }

    public function roles(){
        return $this->belongsToMany('App\Models\Food\Role', 'user_roles', 'user_id', 'role_id');
    }

    // Check Array of roles
    public function hasAnyRoles($roles)
    {
        if($this->roles()->whereIn('slug',$roles)->first()){
            return true;
        }
        return false;
    }

    // Check single roles
    public function hasRole($role)
    {
        if($this->roles()->where('slug',$role)->first()){
            return true;
        }
        return false;
    }

    // language
    public function language(){
        return $this->belongsTo('App\Models\Food\UserLanguage', 'id', 'user_id');
    }

}

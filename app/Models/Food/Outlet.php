<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    public function zone(){
        return $this->belongsTo('App\Models\Food\Zone', 'zone_id', 'id');
    }

    public function manager(){
        return $this->belongsTo('App\Models\User', 'manager_id', 'id');
    }

    public function officer(){
        return $this->belongsTo('App\Models\User', 'officer_id', 'id');
    }

    public function makby(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'cv_code', 'cv_code');
    }

    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('cv_code', 'LIKE', '%'.$val.'%')
        ->orWhere('shop_name', 'LIKE', '%'.$val.'%')
        ->orWhere('shop_address', 'LIKE', '%'.$val.'%')
        ->orWhere('owner', 'LIKE', '%'.$val.'%')
        ->orWhere('owner_mobile', 'LIKE', '%'.$val.'%')
        ->orWhere('owner_mobile_2', 'LIKE', '%'.$val.'%')
        ->orWhere('city', 'LIKE', '%'.$val.'%')
        ->orWhere('district', 'LIKE', '%'.$val.'%')
        ->orWhere('division', 'LIKE', '%'.$val.'%');
    }
}

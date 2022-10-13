<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesSummury extends Model
{
    use HasFactory;
    public function makby(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    // public function all_orders(){
    //     return $this->hasMany('App\Models\Food\Order', 'order_number', 'order_number');
    // }

    public function outlet_details(){
        return $this->hasOne('App\Models\Food\Outlet', 'cv_code', 'cv_code');
    }

    public function all_sales(){
        return $this->hasMany('App\Models\Food\Sale', 'sales_number', 'sales_numb');
    }

    

    


    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('sales_numb', 'LIKE', '%'.$val.'%')
        ->orWhere('sales_date', 'LIKE', '%'.$val.'%')
        ->orWhere('cv_code', 'LIKE', '%'.$val.'%')
        ->orWhere('customer_name', 'LIKE', '%'.$val.'%')
        ->orWhere('customer_number', 'LIKE', '%'.$val.'%')
        ->orWhere('customer_age', 'LIKE', '%'.$val.'%')
        ->orWhere('customer_type', 'LIKE', '%'.$val.'%')
        ->orWhere('total_price', 'LIKE', '%'.$val.'%');
    }
}

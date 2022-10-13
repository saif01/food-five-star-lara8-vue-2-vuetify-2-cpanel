<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\Food\API\InvoiceController;

class OrderSummary extends Model
{

    // protected $appends = ['invoice'];


    use HasFactory;

    public function makby(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function managerby(){
        return $this->belongsTo('App\Models\User', 'manager_id', 'id');
    }

    public function adminby(){
        return $this->belongsTo('App\Models\User', 'admin_id', 'id');
    }

    public function all_orders(){
        return $this->hasMany('App\Models\Food\Order', 'order_number', 'order_number');
    }

    public function outlet_details(){
        return $this->hasOne('App\Models\Food\Outlet', 'cv_code', 'cv_code');
    }

    public function after_sale(){
        return $this->hasMany('App\Models\Food\Sale', 'cv_code', 'cv_code');
    }

    
    // public function getInvoiceAttribute()
    // {
    //     $cv_code = $this->cv_code;
    //     $order_date = $this->order_date;

    //     //dd($cv_code, $order_date);

    //     if( $cv_code && $order_date ){
    //         return InvoiceController::invoice($cv_code, $order_date);
    //     }
        
    //     return false;
    // }
    


    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('order_number', 'LIKE', '%'.$val.'%')
        ->orWhere('order_date', 'LIKE', '%'.$val.'%')
        ->orWhere('cv_code', 'LIKE', '%'.$val.'%')
        ->orWhere('invoice_number', 'LIKE', '%'.$val.'%');
        // ->WhereHas('all_orders.product', function($query) use ($val){
        //     $query->WhereRaw('name_en LIKE ?', '%'.$val.'%')
        //     ->OrWhereRaw('name_bn LIKE ?', '%'.$val.'%');
        // });

        
    }
}

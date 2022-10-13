<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    public function makby(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function sale(){
        return $this->hasOne('App\Models\Food\ProductSale', 'product_order_id', 'id');
    }

    public function order_type(){
        return $this->hasOne('App\Models\Food\ProductOrderType', 'id', 'category');
    }

    protected $appends = ['quantity_order'];

    public function getQuantityOrderAttribute()
    {
        return 1;
    }


    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('product_code', 'LIKE', '%'.$val.'%')
        ->orWhere('type', 'LIKE', '%'.$val.'%')
        ->orWhere('packaging_type', 'LIKE', '%'.$val.'%')
        ->orWhere('weight', 'LIKE', '%'.$val.'%')
        ->orWhere('quantity', 'LIKE', '%'.$val.'%')
        ->orWhere('tp', 'LIKE', '%'.$val.'%')
        ->orWhere('mrp', 'LIKE', '%'.$val.'%')
        ->orWhere('gp', 'LIKE', '%'.$val.'%')
        ->orWhere('gp_percentage', 'LIKE', '%'.$val.'%')
        ->orWhere('remark', 'LIKE', '%'.$val.'%')
        ->orWhereHas('order_type', function($query) use ($val){
            $query->WhereRaw('name_en LIKE ?', '%'.$val.'%');
        });
    }
}

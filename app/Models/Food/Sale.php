<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function foods(){
        return $this->belongsTo('App\Models\Food\ProductSale', 'product_id','id');
    }

    public function summury(){
        return $this->belongsTo('App\Models\Food\SalesSummury', 'sales_number','sales_numb');
    }

    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('customer_number', 'LIKE', '%'.$val.'%')
        ->orWhere('sales_number', 'LIKE', '%'.$val.'%');
    }
}

<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function product_details(){
        return $this->hasOne('App\Models\Food\ProductOrder', 'id', 'product_id');
    }

    public function order_summary(){
        return $this->hasOne('App\Models\Food\OrderSummary', 'order_number', 'order_number');
    }


    public function product(){
        return $this->hasOne('App\Models\Food\ProductSale', 'product_order_id', 'product_id');
    }


    public function product_order_table(){
        return $this->hasOne('App\Models\Food\ProductOrder', 'id', 'product_id');
    }


}

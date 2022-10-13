<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartsoftInvoice extends Model
{
    use HasFactory;

    public function product(){
        return $this->hasOne('App\Models\Food\ProductOrder', 'product_code', 'PRODUCT_CODE');
    }

    public function outlet(){
        return $this->hasOne('App\Models\Food\Outlet', 'cv_code', 'CUSTOMER_CODE');
    }
}

<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BanglaConverter;


class AdminOrder extends Model
{
    use HasFactory;

    public function foods(){
        return $this->belongsTo('App\Models\Food\ProductSale','product_id','id');
    }

    protected $appends = ['price_bn', 'price_offer_bn', 'quantity_bn'];

    public function getPriceBnAttribute()
    {
        return BanglaConverter::en2bn($this->price);
    }

    public function getPriceOfferBnAttribute()
    {
        return BanglaConverter::en2bn($this->price_offer);
    }

    public function getQuantityBnAttribute()
    {
        return BanglaConverter::en2bn($this->quantity);
    }
}

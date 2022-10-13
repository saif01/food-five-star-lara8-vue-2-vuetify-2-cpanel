<?php

namespace App\Models\Food;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BanglaConverter;

class ProductSale extends Model
{
    use HasFactory;

    protected $appends = ['price_bn', 'price_offer_bn', 'quantity', 'quantity_bn'];

    public function makby(){
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function sales_product(){
        return $this->belongsTo('App\Models\Food\ProductOrder', 'product_order_id', 'id');
    }

    public function sale_foods(){
        return $this->belongsTo('App\Models\Food\Sale', 'id','product_id');
    }

    public function getPriceBnAttribute()
    {
        return BanglaConverter::en2bn($this->price);
    }

    public function getPriceOfferBnAttribute()
    {
        return BanglaConverter::en2bn($this->price_offer);
    }

    public function getQuantityAttribute()
    {
        return 1;
    }

    public function getQuantityBnAttribute()
    {
        return BanglaConverter::en2bn($this->quantity);
    }

    public function type(){
        return $this->belongsTo('App\Models\Food\ProductType', 'type_id', 'id');
    }

    public function free(){
        return $this->belongsTo('App\Models\Food\ProductSale', 'free_item', 'id');
    }

    public function scopeSearch($query, $val='')
    {
        return $query
        ->where('name_en', 'LIKE', '%'.$val.'%')
        ->orWhere('name_bn', 'LIKE', '%'.$val.'%')
        ->orWhere('price', 'LIKE', '%'.$val.'%')
        ->orWhere('price_offer', 'LIKE', '%'.$val.'%')
        ->orWhereHas('sales_product', function($query) use ($val){
            $query->WhereRaw('product_code LIKE ?', '%'.$val.'%')
            ->orWhereRaw('type LIKE ?', '%'.$val.'%');
        })
        ->orWhereHas('type', function($query) use ($val){
            $query->WhereRaw('name_en LIKE ?', '%'.$val.'%');
        });
    }
}

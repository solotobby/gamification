<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPlaceProduct extends Model
{
    use HasFactory;
    protected $table = 'market_place_products';

    protected $fillable = ['user_id', 'name', 'amount', 'total_payment', 'commission_payment', 'commission', 'type', 'banner', 'product', 'product_id', 'views'];

    public function sales(){
        return $this->hasMany(MarketPlacePayment::class, 'market_place_product_id');
    }

}

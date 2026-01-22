<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPlacePayment extends Model
{
    use HasFactory;
    protected $table = 'market_place_payments';

    protected $fillable = [
        'market_place_product_id',
        'name',
        'amount',
        'email',
        'ref',
        'url',
        'is_complete',
        'user_id',
        'download_count'
    ];
}

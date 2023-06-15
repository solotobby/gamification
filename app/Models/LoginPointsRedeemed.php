<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginPointsRedeemed extends Model
{
    use HasFactory;

    protected $table = 'login_points_redeemed';

    protected $fillable = ['user_id', 'point', 'amount'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}

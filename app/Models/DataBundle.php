<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBundle extends Model
{
    use HasFactory;

    protected $table = "data_bundles";

    protected $fillable = ['name', 'amount', 'status'];
}

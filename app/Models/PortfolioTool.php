<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioTool extends Model
{
    use HasFactory;

    protected $fillable = ['portfolio_id', 'tool_id'];
}

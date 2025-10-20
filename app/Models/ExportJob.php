<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'email',
        'file_path',
        'error_message',
        'completed_at'
    ];
}

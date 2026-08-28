<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationBadge extends Model
{
    public $timestamps = false; // Disable automatic timestamps
  protected $fillable = [
      'key',
      'label'
  ];

}

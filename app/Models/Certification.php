<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'issuer',
        'issue_date',
        'expiry_date',
        'credential_id',
        'verification_url',
        'file_path',
    ];
}

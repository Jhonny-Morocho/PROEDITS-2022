<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropboxToken extends Model
{
    public $timestamps=true;
    protected $fillable = [
        'token_type',
        'access_token',
        'expires_in',
    ];
}

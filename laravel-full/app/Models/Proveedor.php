<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//roles
use Spatie\Permission\Traits\HasRoles;


class Proveedor extends Authenticatable
{
    use HasFactory,HasRoles;
    protected $guarded=[
        'id',
        'created_at',
        'updated_at'
    ];
    public function guardName(){
        return "web";
    }
}

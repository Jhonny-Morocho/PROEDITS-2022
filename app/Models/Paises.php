<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model{
    //nombre de la tabla
    protected $table="paises";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "iso",
        "nombre",
        "created_at",
        "updated_at"
    ];
}

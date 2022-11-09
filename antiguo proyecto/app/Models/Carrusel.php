<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrusel extends Model{
    protected $table="carrusel";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "archivo",
        "estado",
        "created_at",
        "updated_at"
    ];
}

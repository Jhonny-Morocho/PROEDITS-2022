<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genero extends Model{
    //nombre de la tabla
    protected $table="genero";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "genero",
        "estado",
        "created_at",
        "updated_at"
    ];
    public function producto(){
        return $this->hasOne('App\Models\Producto','id_genero');
    }
}

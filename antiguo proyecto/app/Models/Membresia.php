<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model{
    protected $table="membresia";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "nombre",
        "precio",
        "descargas",
        "num_dias",
        "estado",
        "created_at",
        "updated_at",
    ];
    public function clienteMembresia(){
        return $this->hasMany('App\Models\ClienteMembresia','id_cliente');
    }
}

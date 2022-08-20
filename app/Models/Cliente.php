<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model{
    //nombre de la tabla
    protected $table="cliente";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamp=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "nombre",
        "apellido",
        "correo",
        "password",
        "tipo_usuario",
        "saldo",
        "proveedor",
        "estado",
        "created_at",
        "updated_at",
    ];
    public function pedido(){
        return $this->hasMany('App\Models\Pedido','id_cliente');
    }
}

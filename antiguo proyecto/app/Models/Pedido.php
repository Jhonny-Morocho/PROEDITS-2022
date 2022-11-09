<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model{
    protected $table="pedido";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "id_cliente",
        "form_factura",
        "total_real",
        "total_venta",
        "metodo_compra",
        "id_transaccion",
        "pais_compra",
        "aceptar_terminos",
        "documento_identidad",
        "direccion",
        "telefono",
        "correo",
        "ciudad",
        "nombre",
        "apellido",
        "ip_cliente",
        "estado",
        "created_at",
        "updated_at"
    ];
    //lista negra campos que no queren que se encuentren facilmente
    public function cliente(){
        return $this->hasMany('App\Models\Cliente','id_cliente');
    }
}

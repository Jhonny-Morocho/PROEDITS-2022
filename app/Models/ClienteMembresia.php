<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteMembresia extends Model{
    protected $table="cliente_membresia";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "id_cliente",
        "id_membresia",
        "id_transaccion",
        "fecha_inicio",
        "fecha_culminacion",
        "precio_venta",
        "precio_real",
        "precio_unidad",
        "ciudad",
        "pais_compra",
        "ip_cliente",
        "nombre",
        "apellido",
        "metodo_compra",
        "correo",
        "direccion",
        "telefono",
        "acepta_terminos",
        "documento_identidad",
        "descargas_total",
        "descargas_sobrantes",
        "form_factura",
        "estado",
        "created_at",
        "updated_at",
    ];
    //lista negra campos que no queren que se encuentren facilmente
    public function membresia(){
        return $this->hasMany('App\Models\Membresia','id_membresia');
    }
    public function cliente(){
        return $this->hasMany('App\Models\Pedido','id_cliente');
    }

}

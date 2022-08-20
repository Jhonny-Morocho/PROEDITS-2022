<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model{
    protected $table="pedido_producto";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "id_cliente",
        "id_producto",
        "id_pedido",
        "metodo_compra",
        "precio_real",
        "precio_venta",
        "estado",
        "estado_pago_proveedor",
        "created_at",
        "updated_at",
    ];
    //lista negra campos que no queren que se encuentren facilmente
    public function producto(){
        return $this->hasMany('App\Models\Producto','id_cliente');
    }
    public function pedido(){
        return $this->hasMany('App\Models\Pedido','id_pedido');
    }
    public function cliente(){
        return $this->hasMany('App\Models\Cliente','id_cliente');
    }
}

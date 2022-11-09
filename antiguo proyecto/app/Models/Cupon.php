<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model{
    protected $table="cupon";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "descuento",
        "monto",
        "sms_promocion",
        "inicio",
        "expira",
        "estado",
        "created_at",
        "updated_at"
    ];
}

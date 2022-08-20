<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model{
    //nombre de la tabla
    protected $table="proveedor";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "nombre",
        "apellido",
        "apodo",
        "correo",
        "tipo_usuario",
        "password",
        "img",
        "estado",
        "created_at",
        "updated_at"
    ];
    //lista negra campos que no queren que se encuentren facilmente
/*     public function docente(){
        return $this->hasOne('App\Models\Docente','fk_usuario');
    }
    public function estudiante(){
        return $this->hasOne('App\Models\Estudiante','fk_usuario');
    }
    public function empleador(){
        return $this->hasOne('App\Models\Empleador','fk_usuario');
    } */

    public function producto(){
        return $this->hasMany('App\Models\Producto','id_proveedor');
    }

}

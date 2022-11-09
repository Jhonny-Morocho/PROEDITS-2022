<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model{
    //nombre de la tabla
    protected $table="productos";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamps=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "id_proveedor",
        "id_genero",
        "precio",
        "url_descarga",
        "url_directorio",
        "estado",
        "tipo_archivo",
        "caratula",
        "created_at",
        "updated_at",
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
}

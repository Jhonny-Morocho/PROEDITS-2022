<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proveedor;
use App\Models\Genero;
class Producto extends Model
{
    use HasFactory;
    protected $table="productos";
       /**
     * Get the genero associated with the producto.
     */
    public function genero()
    {
        return $this->hasOne(Genero::class);
    }
          /**
     * Get the proveedor associated with the producto.
     */
    public function proveedor()
    {
        return $this->hasOne(Proveedor::class);
    }
}

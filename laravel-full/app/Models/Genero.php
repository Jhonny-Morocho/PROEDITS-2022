<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
class Genero extends Model
{
    use HasFactory;
    protected $table="generos";
    protected $fillable = [
        'genero',
        'estado',
        'fecha'
    ];
    public function producto()
    {
        return $this->hasOne(Producto::class);
    }
}

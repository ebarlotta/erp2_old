<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descripcion',
        'precio_compra',
        'existencia',
        'stock_minimo',
        'lote',
        'unidads_id',
        'categoriaproductos_id',
        'estados_id',
        'proveedor_id',
        'ruta',
        'barra',
        'qr',
        'barra_proveedor',
        'empresa_id',
    ];

    //Relacion de uno a muchos 

    public function unidad()
    {
        return $this->hasMany('App\Models\Unidad','id');
    }
}

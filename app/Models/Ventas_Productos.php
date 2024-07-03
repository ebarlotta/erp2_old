<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas_Productos extends Model
{
    use HasFactory;

    protected $fillable=[
        'productos_id',
        'ventas_id',
        'cantidad',
        'precio',
        'user_id',
        'orden',
    ];

    public function descripcionp()
    {
        return $this->hasMany('App\Models\Producto','id');
    }
}

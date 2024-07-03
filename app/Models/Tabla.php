<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabla extends Model
{
    use HasFactory;
    //Relacion uno a muchos inversa

    protected $fillable=[
        'name',
        'encabezadocolumna',
        'cantidadfila',
        'cantidadcolumna',
        'empresa_id',
    ];
    
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}

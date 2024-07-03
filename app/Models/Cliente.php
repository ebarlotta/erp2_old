<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'direccion',
        'cuil',
        'telefono',
        'email',
        'empresa_id',
        'iva_id',
    ];
    //Relacion uno a muchos inversa

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    //Relación uno a muchos

    public function comprobantes() {
        return $this->hasMany(Comprobante::class);
    }
}

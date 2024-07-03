<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaModulo extends Model
{
    use HasFactory;
    protected $fillable=[
        'empresa_id',
        'modulo_id',
    ];
    //Relacion uno a muchos inversa

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}

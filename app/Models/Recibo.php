<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;

    protected $fillable=[
        'perpago',
        'lugarpago',
        'fechapago',
        'totalhaberes',
        'noremunetativo',
        'descuentos',
        'perultimaliq',
        'fechaultliq',
        'estado',
        'empleado_id',
        'categoriaprofesional_id',
    ];
    
    //Relacion uno a muchos inversa
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    //Relacion de uno a muchos
    public function conceptosrecibos()
    {
        return $this->hasMany(ConceptoRecibo::class);
    }

    public function CategoriaProfesional()
    {
        return $this->hasOne(Categoriaprofesional::class,'id');
    }

    public function DatosEmpleado()
    {
        return $this->hasOne(Empleado::class,'id');
    }
}

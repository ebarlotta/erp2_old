<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'domicilio',
        'cuil',
        'telefono',
        'legajo',
        'dni',
        'nacimiento',
        'ingreso',
        'estadocivil',
        'tipocontratacion',
        'regimen',
        'banco',
        'nrocuentabanco',
        'jornalizado',
        'mensualizado',
        'hora',
        'unidad',
        'seccion',
        'activo',
        'baja',
        'categoriaprofesional_id',
        'empresa_id',
        'user_id'
    ];
    //Relacion uno a muchos inversa

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    //Relación de uno a muchos
    public function recibos()
    {
        return $this->hasMany(Recibo::class);
    }

    public function CategoriaProfesional()
    {
        return $this->hasOne(Categoriaprofesional::class,'id','categoriaprofesional_id');
    }
}

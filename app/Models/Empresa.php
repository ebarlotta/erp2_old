<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'direccion',
        'cuit',
        'ib',
        'imagen',
        'establecimiento',
        'telefono',
        'actividad',
        'actividad1',
    ];

    //Relacion de uno a muchos 
      
    public function cuentas()
    {
        return $this->hasMany('App\Models\Cuenta');
    }

    public function areas()
    {
        return $this->hasMany('App\Models\Area');
    }

    public function comprobantes()
    {
        return $this->hasMany('App\Models\Comprobante');
    }

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class);
    }

    public function clientes()
    {
        return $this->hasMany('App\Models\Cliente');
    }
    
    public function empleados()
    {
        return $this->hasMany('App\Models\Empleado');
    }

    public function categoriaprofesionales()
    {
        return $this->hasMany('App\Models\Categoriaprofesional');
    }

    public function tablas()
    {
        return $this->hasMany('App\Models\Tabla');
    }

    public function empresausuarios()
    {
        return $this->hasMany('App\Models\EmpresaUsuario');
    }

    public function empresamodulos()
    {
        return $this->hasMany('App\Models\EmpresaModulo');
    }
}

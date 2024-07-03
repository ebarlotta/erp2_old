<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'comprobante',
        'detalle',
        'BrutoComp',
        'ParticIva',
        'MontoIva',
        'ExentoComp',
        'ImpInternoComp',
        'PercepcionIvaComp',
        'RetencionIB',
        'RetencionGan',
        'NetoComp',
        'MontoPagadoComp',
        'CantidadLitroComp',
        'Cerrado',
        'Anio',
        'PasadoEnMes',
        'iva_id',
        'area_id',
        'cuenta_id',
        'user_id',
        'empresa_id',
        'cliente_id',
    ];

    //Relación uno a uno

    public function iva()
    {
        return $this->belongsTo('App\Models\Iva');
    }


    //Relacion uno a muchos inversa

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }
}

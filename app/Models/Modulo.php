<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    //Relación de uno a muchos
    public function empresamodulos()
    {
        return $this->hasMany(EmpresaModulo::class);
    }

    public function modulousuarios(){
        return $this->hasMany(ModuloUsuario::class);
    }
}

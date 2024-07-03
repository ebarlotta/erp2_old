<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaUsuario extends Model
{
    use HasFactory;

    protected $fillable=[
        'empresa_id',
        'user_id',
    ];
    //Relacion uno a muchos inversa

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }
}

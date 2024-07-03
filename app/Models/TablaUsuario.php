<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablaUsuario extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tabla_id',];
    
    //Relación uno a muchos invertida
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function tabla()
    {
        return $this->belongsTo(Tabla::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuloUsuario extends Model
{
    use HasFactory;
    protected $fillable = [
        'modulo_id',
        'user_id',
    ];

        //Relación de uno a muchos inversa
        public function modulo()
        {
            return $this->belongsTo(Modulo::class);
        }
    
        public function usuario(){
            return $this->belongsTo(User::class);
        }
}

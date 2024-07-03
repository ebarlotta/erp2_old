<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    use HasFactory;

    //Relacion de uno a muchos
    public function conceptosrecibos()
    {
        return $this->hasMany(ConceptoRecibo::class);
    }
}

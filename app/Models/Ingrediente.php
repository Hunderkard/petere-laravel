<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function platos(){
        return $this->belongsToMany(Plato::class, 'ingrediente_plato', 'ingrediente_id', 'plato_id')
                    ->withPivot('cantidad')
                    ->as('receta');
    }
}

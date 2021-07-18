<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    public function ingredientes(){
        return $this->belongsToMany(Ingrediente::class, 'ingrediente_plato', 'plato_id', 'ingrediente_id')
                    ->withPivot('cantidad')
                    ->as('receta');
    }
}

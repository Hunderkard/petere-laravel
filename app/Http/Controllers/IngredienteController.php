<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IngredienteController extends Controller
{
    //hi GET /ingredientes
    //pl Ingredientes.
    public function index(){return Ingrediente::all();}

    //hi POST /ingredientes
    //ti Formulario.
    //pl Registro BD.
    public function store(Request $req)
    {
        $ing = new Ingrediente;
        $ing->nombre = $req->nombre; 

        $ing->unidad_de_medida = $req->unidad;
        $ing->stock = $req->cantidad;    
        $ing->valor = $req->precio / $req->cantidad; //el

        $ing->observaciones = $req->observaciones;

        $ing->proveedor_id = $req->proveedor;

        //vo $ing->fecha_compra = $req->fecha_compra;
        //vo $ing->fecha_caducidad = $req->fecha_caducidad;

        if($req->hasFile('imagen')){
            $ing->foto = str_replace(' ', '_', $req->nombre) . '.png';
            Storage::putFileAs(
                'public/imagenes/ingrediente',
                $req->file('imagen'),
                str_replace(' ', '_', $req->nombre) . '.png'
            );
        }
        else{
           $ing->foto = 'sin_imagen.png';
        }
        
        $ing->save();
    }

    //hi POST /ingredientes/{id}
    //ti ID del ingrediente.
    //pl Ingrediente.
    public function show($id){ return Ingrediente::find($id);}

    //hi PUT /ingredientes/{id}
    //ti Formulario
    //ti ID
    //pl Update registro BD.
    public function update(Request $req, $id)
    {
        $ing = Ingrediente::find($id);
        $ing->nombre = $req->nombre;
        $ing->valor = $req->precio;
        $ing->stock = $req->stock;
        $ing->unidad_de_medida = $req->unidad;
        $ing->observaciones = $req->observaciones;
        $ing->save();
    }
    
    //hi POST /imagen/ingrediente
    public function reemplazarImagen(Request $req)
    {
        $ing = Ingrediente::find($req->id);
        if($ing->foto != 'sin_imagen.png'){
            Storage::delete(
                'public/imagenes/ingrediente/' . $ing->foto
            );
        }
        Storage::putFileAs(
            'public/imagenes/ingrediente', 
            $req->file('imagen'), 
            str_replace(' ', '_', $req->nombre) . '.png'
        );
        $ing->foto = str_replace(' ', '_', $req->nombre) . '.png';
        $ing->save();
    }

    //hi DELETE /ingredientes/{id}
    //ti ID
    //pl Borra registro BD.
    public function destroy($id)
    {  
        $ing = Ingrediente::find($id);
        if($ing->foto != 'sin_imagen.png'){
            Storage::delete(
                'public/imagenes/ingrediente/' . $ing->foto
            );
        }
        $ing->delete();
        DB::table('ingrediente_plato')->where('ingrediente_id', '=', $id)->delete();

    }

    //hi PUT /compra
    //ti FORMULARIO
    //pl SUMA Y HACE LA MEDIA
    public function compra(Request $req){

        $compras = count($req->compras);

        for ($i=0; $i < $compras; $i++) { 
            $ing = Ingrediente::find($req->compras[$i]['id']);

            $valor_c = $req->compras[$i]['coste'] / $req->compras[$i]['cantidad'];

            $cantidad_f = $req->compras[$i]['cantidad'] + $ing->stock;
            $valor_f = (($req->compras[$i]['cantidad'] * $valor_c) + ($ing->stock * $ing->valor))/$cantidad_f;

            $ing->stock = $cantidad_f;
            $ing->valor = $valor_f;
            $ing->save(); 
        }
    }

    //hi PUT /perdida
    //ti FORMULARIO
    //pl RESTA
    public function perdida(Request $req){

        $perdidas = count($req->perdidas);
        
        for ($i=0; $i < $perdidas; $i++) { 
            $ing = Ingrediente::find($req->perdidas[$i]['id']);

            $ing->stock -= $req->perdidas[$i]['cantidad'];
            $ing->save(); 
        }
    }
}
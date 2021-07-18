<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\Plato;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PlatoController extends Controller
{
    //hi GET platos
    public function index(){return Plato::all();}

    //hi POST platos
    public function store(Request $req)
    {
        $plato = new Plato;
        $plato->nombre = $req->nombre;
        $plato->observaciones = $req->observaciones;
        $plato->precio = $req->precio;
        if($req->hasFile('imagen')){
            $plato->foto = str_replace(' ', '_', $req->nombre) . '.png';
            Storage::putFileAs(
                'public/imagenes/plato',
                $req->file('imagen'),
                str_replace(' ', '_', $req->nombre) . '.png'
            );
        }
        else{
           $plato->foto = 'sin_imagen.png';
        }
        $plato->save();

        $pivote = $req->except('nombre', 'observaciones', 'precio');
        $datos = floor(count($pivote)/2);
        error_log($datos);
        $id = $plato->id;
        for ($i=0; $i < $datos; $i++) { 
               DB::insert('insert into ingrediente_plato 
               (plato_id, ingrediente_id, cantidad) value (?,?,?)',
               [$id, $pivote[$i . 'id'], $pivote[$i . 'cant']]);

            }
    }

    //hi GET platos/{id}
    public function show($id){ return Plato::find($id);}

    //hi PUT platos/{id}
    public function update(Request $req, $id)
    {
        $plato = Plato::find($id);
        $plato->nombre = $req->nombre;
        $plato->precio = $req->precio;
        $plato->observaciones = $req->observaciones;
        $plato->save();

        DB::table('ingrediente_plato')->where('plato_id', '=', $id)->delete();
        
        $pivote = $req->except('nombre', 'observaciones', 'precio');
        $datos = count($pivote['ingredientes']);
        $id = $plato->id;
        
        for ($i=0, $ing = 0; $i < $datos; $i++, $ing++) { 
               DB::table('ingrediente_plato')->insert([
                   'plato_id' => $id, 
                   'ingrediente_id' => Ingrediente::where('nombre', $pivote['ingredientes'][$i])->get()[0]['id'],
                   'cantidad' => $pivote['ingredientes'][$i]['cantidad'],
               ]);
        }
        
    }

    //hi POST imagen/plato
    public function reemplazarImagen(Request $req)
    {
        $plato = Plato::find($req->id);
        if($plato->foto != 'sin_imagen.png'){
            Storage::delete(
                'public/imagenes/plato/' . $plato->foto
            );
        }
        Storage::putFileAs(
            'public/imagenes/plato',
            $req->file('imagen'),
            str_replace(' ', '_', $req->nombre) . '.png'
        );
        $plato->foto = str_replace(' ', '_', $req->nombre) . '.png';
        $plato->save();
    }

    //hi GET receta/{id}
    public function getReceta($id)
    {
        $plato = Plato::find($id);
        $ingredientes = [];
        foreach($plato->ingredientes as $ingrediente){
            $ingredientes[$ingrediente->nombre] =
            $ingrediente->receta->cantidad;  
        }

         return $ingredientes;
    }

    //hi GET recetas
    public function getRecetas()
    {
        /* [Plato, Ingredientes[], Agotado] */
        $platos = Plato::all();

        $menu = [];

        foreach($platos as $plato_id => $plato){
            $novo = [[],[], false];
            $receta = $plato->ingredientes;
            array_push($novo[0], $plato->precio);
            array_push($novo[0], $plato->foto);
            array_push($novo[0], $plato->nombre);
            array_push($novo[0], $plato->tipo);
            array_push($novo[0], $plato->id);
            $novo[1] = [];

            foreach($receta as $ingrediente){
                if($ingrediente->stock <= 0 ){
                    $novo[2] = true;
                    array_push($novo[1], 'T' . $ingrediente->nombre);
                }
                else{
                    array_push($novo[1], 'N' . $ingrediente->nombre);
                }

                
            }
            array_push($menu, $novo);
        }

        return response()->json([$menu][0]);
    }
    //hi DELETE platos/{id}
    public function destroy($id)
    {
        $plato = Plato::find($id);
        if($plato->foto != 'sin_imagen.png'){
            Storage::delete(
                'public/imagenes/plato/' . $plato->foto
            );
        }
        $plato->delete();

        DB::table('ingrediente_plato')->where('plato_id', '=', $id)->delete();
    }

    //hi GET restar_plato/{id}
    public function restar_ingredientes(Request $req)
    {
        try{
            $plato = Plato::where('nombre', $req->plato_nombre)->get()[0];
            
            foreach($plato->ingredientes as $ingrediente){
                if($ingrediente->stock > 0){
                $quitan = $ingrediente->receta->cantidad;

                $ingrediente->stock -= $quitan;
                $ingrediente->save();
            }
            }
        }
        catch (Exception $e){
            return response()->json("No existe ese plato.");
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Plato;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MesaController extends Controller
{
    public function check(Request $req){
        $mesa = Mesa::where('numero', $req->numero)->get()[0];

        return response()->json($mesa->codigo == $req->codigo);

    }

    //pl Devuelve cuántas mesas hay. En este momento 15.
    public function contar(){return response()->json(Mesa::all());}

    /* $mesa_id, $plato_nombre) */
    public function cuenta(Request $req ){
       try{
        $mesa = Mesa::find($req->mesa);
        $plato = Plato::where('nombre', $req->plato)->get()[0];
        if($mesa && $plato) {
        error_log("LLegamos También");

            DB::table('mesa_plato')->insert([
                'mesa_id' => $req->mesa,
                'plato_id' => $plato->id,
                'precio' => $plato->precio,
                'plato_nombre' => $req->plato
            ]);
            foreach ($plato->ingredientes as $ing) {
                $ing->stock -= $ing->receta->cantidad;
                $ing->save();
            }
            return response()->json(["Llegó bien, en teoria."]);
            // $plato->ingredientes[0]->stock = 1000;
            // $plato->ingredientes[0]->save();
            // return response()->json([$plato->ingredientes[0]->receta]);
        }
        else return response()->json(["mal"]);
        }
        catch(Exception $e){
            return response()->json([ ' Error: ' + $e]);
        }
   }

    public function cobrar(Request $req){
        try{
            $mesa = Mesa::find($req->mesa);
            if($mesa) {

                $data = DB::table('mesa_plato')->where('mesa_id', $req->mesa)->get();
                
                $platos = [];
                $precio = 0;
                foreach ($data as $plato) {
                    $precio += $plato->precio;
                    array_push($platos, [$plato->plato_nombre, $plato->precio]);
                }

            
                return response()->json([$precio, $platos]);
            }
            else return response()->json(["Error"]);
        }
        catch(Exception $e){
            return response()->json(["No hay platos en esa mesa." . $e]);
        }
    }

    public function limpiar(Request $req){
        try{
            $mesa = Mesa::find($req->mesa); 
            if($mesa) {
                $data = DB::table('mesa_plato')->where(
                    'mesa_id', '=', $req->mesa,
                )->get();
                $precio = 0;
                foreach ($data as $plato) {
                    $precio += $plato->precio;
                }
            $hora_peninsular = time() + (60*60);
                DB::table('caja')->insert([
                    "mesa_id" => $req->mesa,
                    "cobro" => $precio,
                    "hora" => date('H:i:s', $hora_peninsular),
                    "dia" => date('Y-m-d'),
                ]);

                DB::table('mesa_plato')->where(
                    'mesa_id', '=', $req->mesa,
                )->delete();
            }
            else return response()->json(["mal"]);
        }
        catch(Exception $e){
        }  
    }

    public function caja(){
        return DB::table('caja')->get();
    }

    public function cambiar(Request $req){
        error_log('cambiado el codigo');
        try{
            $mesa = Mesa::where('numero', $req->mesa_num)->get()[0];
            if($req->codigo){
                $mesa->codigo = $req->codigo;
                $mesa->save();
            }
        }
        catch(Exception $e){
            return response()->json(["Esa mesa no existe."]);
        }


    }
}

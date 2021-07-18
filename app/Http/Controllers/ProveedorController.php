<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Storage;

class ProveedorController extends Controller
{
    //hi GET /proveedores
    //pl Devuelve los proveedores.
    public function index(){return Proveedor::all();}

    //hi POST /proveedores
    //ti Formulario.
    //pl Registro BD.
    public function store(Request $req)
    {
        $pro = new Proveedor;
        $pro->nombre = $req->nombre;

        if($req->hasFile('imagen')){
            $pro->foto = $req->nombre . '.png';
            Storage::putFileAs(
                'public/imagenes/proveedor',
                $req->file('imagen'),
                str_replace(' ', '_', $req->nombre) . '.png'
            );
        }
        else{
            $pro->foto = 'sin_imagen.png';
        }

        $pro->save();
    }
    //hi POST /proveedores/{id}
    //ti ID del proveedor.
    //pl Proveedor.
    public function show($id){ return Proveedor::find($id);}

    //hi PUT /proveedor/{id}
    //ti Formulario
    //ti ID
    //pl Updata registro BD.
    public function update(Request $req, $id)
    {
        $pro = Proveedor::find($id);
        $pro->nombre = $req->nombre;
        $pro->save();
    }

    //hi POST /imagen/proveedor
    public function reemplazarImagen(Request $req)
    {
        $pro = Proveedor::find($req->id);
        if($pro->foto != 'sin_imagen.png'){
            Storage::delete(
                'public/imagenes/proveedor/' . $pro->foto
            );
        }
        Storage::putFileAs(
            'public/imagenes/proveedor',
            $req->file('imagen'),
            str_replace(' ', '_', $req->nombre) . '.png'
        );
        $pro->foto = str_replace(' ', '_', $req->nombre) . '.png';
        $pro->save();
    }

    //hi DELETE /proveedor/{id}
    //ti ID
    //pl Borra registro BD.
    public function destroy($id)
    {
        $pro = Proveedor::find($id);
        if($pro->foto != 'sin_imagen.png'){
            Storage::delete(
                'public/imagenes/proveedor/' . $pro->foto
            );
        }
        $pro->delete();
    }
}
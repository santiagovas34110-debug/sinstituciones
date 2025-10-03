<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escuelas;

class InstitucionesController extends Controller
{
    public function home(){
        return view('home');
    }

    public function index(){
        $escuelas=Escuelas::get();
        return view('escuelas')->with(compact('escuelas'));
    }

    public function create(request $request){
        $nombre= $request->nombre;
        $ubicacion=$request->ubicacion;
        $contacto_nombre= $request->contacto_nombre;
        $contacto_rol= $request->contacto_rol;
        $contacto_telefono= $request->contacto_telefono;
        $contacto_email= $request->contacto_email;
        $contacto_documento= $request->contacto_documento;
        $contacto_tipo_documento= $request->contacto_tipo_documento;
        $codigo_dane= $request->codigo_dane;
        $nit= $request->nit;    

        Escuelas::create([
            "nombre"=>$nombre,
            "ubicacion"=>$ubicacion,
            "contacto_nombre"=>$contacto_nombre,
            "contacto_rol"=>$contacto_rol,
            "contacto_telefono"=>$contacto_telefono,
            "contacto_email"=>$contacto_email,
            "contacto_documento"=>$contacto_documento,
            "contacto_tipo_documento"=>$contacto_tipo_documento,
            "codigo_dane"=>$codigo_dane,
            "nit"=>$nit
        ]);

        return back()->with('success','Se ha creado la escuela satisfactriamente');
        
      
    }
    public function delete(Request $request){
        $id=$request->id;

        Escuelas::where('id',$id)->delete();
        
        return back()->with('warning','se elimino la escuela satisfactoriamente');


    }

    public function update(Request $request){
        $id=$request->id;
        $nombre= $request->nombre;
        $ubicacion=$request->ubicacion;
        $contacto_nombre= $request->contacto_nombre;
        $contacto_rol= $request->contacto_rol;
        $contacto_telefono= $request->contacto_telefono;
        $contacto_email= $request->contacto_email;
        $contacto_documento= $request->contacto_documento;
        $contacto_tipo_documento= $request->contacto_tipo_documento;
        $codigo_dane= $request->codigo_dane;
        $nit= $request->nit;    

        Escuelas::where('id',$id)->update([
            "nombre"=>$nombre,
            "ubicacion"=>$ubicacion,
            "contacto_nombre"=>$contacto_nombre,
            "contacto_rol"=>$contacto_rol,
            "contacto_telefono"=>$contacto_telefono,
            "contacto_email"=>$contacto_email,
            "contacto_documento"=>$contacto_documento,
            "contacto_tipo_documento"=>$contacto_tipo_documento,
            "codigo_dane"=>$codigo_dane,
            "nit"=>$nit
        ]);

        return back()->with('success',' Escuela actualizada satisfactriamente');

    }
}

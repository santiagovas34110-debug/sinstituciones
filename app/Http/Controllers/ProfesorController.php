<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use App\Models\Escuelas;


class ProfesorController extends Controller
{
   public function index()
{
    $profesores = Profesor::with('escuela')->get();
    $escuelas = Escuelas::get(); // 🔥 agregamos las escuelas
    return view('profesores.index', compact('profesores', 'escuelas'));
}

public function profesores()
{
    $profesores = Profesor::with('escuela')->get();
    $escuelas = Escuelas::all(); // 🔥 igual aquí
    return view('profesores', compact('profesores', 'escuelas'));
}

    public function crearProfesor(Request $request){
        $id_escuela = $request->id_escuela;
        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $documento = $request->documento;
        $fecha_nacimiento = $request->fecha_nacimiento;
        $genero = $request->genero;
        $email = $request->email;
        $telefono = $request->telefono;
        $direccion = $request->direccion;
        $titulo = $request->titulo;
        $especialidad = $request->especialidad;
        $fecha_ingreso = $request->fecha_ingreso;
        $salario = $request->salario;
        $grado = $request->grado;
        $activo = $request->activo ?? true;

        Profesor::create([
            "id_escuela" => $id_escuela,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "documento" => $documento,
            "fecha_nacimiento" => $fecha_nacimiento,
            "genero" => $genero,
            "email" => $email,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "titulo" => $titulo,
            "especialidad" => $especialidad,
            "fecha_ingreso" => $fecha_ingreso,
            "salario" => $salario,
            "grado" => $grado,  
            "activo" => $activo
        ]);

        return back()->with('success','Se ha creado el profesor satisfactoriamente');
    }

    public function eliminarProfesor(Request $request){
        $id = $request->id;

        Profesor::where('id',$id)->delete();
        
        return back()->with('warning','Se eliminó el profesor satisfactoriamente');
    }

    public function updateProfesor(Request $request){
        $id = $request->id;
        $id_escuela = $request->id_escuela;
        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $documento = $request->documento;
        $fecha_nacimiento = $request->fecha_nacimiento;
        $genero = $request->genero;
        $email = $request->email;
        $telefono = $request->telefono;
        $direccion = $request->direccion;
        $titulo = $request->titulo;
        $especialidad = $request->especialidad;
        $fecha_ingreso = $request->fecha_ingreso;
        $salario = $request->salario;
        $grado = $request->grado;   
        $activo = $request->activo ?? true;

        Profesor::where('id',$id)->update([
            "id_escuela" => $id_escuela,
            "nombre" => $nombre,
            "apellido" => $apellido,
            "documento" => $documento,
            "fecha_nacimiento" => $fecha_nacimiento,
            "genero" => $genero,
            "email" => $email,
            "telefono" => $telefono,
            "direccion" => $direccion,
            "titulo" => $titulo,
            "especialidad" => $especialidad,
            "fecha_ingreso" => $fecha_ingreso,
            "salario" => $salario,
            "grado" => $grado,  
            "activo" => $activo
        ]);

        return back()->with('success','Profesor actualizado satisfactoriamente');
    }
}
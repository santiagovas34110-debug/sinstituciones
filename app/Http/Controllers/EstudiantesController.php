<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiantes;
use App\Models\Profesor;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowFormatter;
use App\Imports\EstudiantesImport;

class EstudiantesController extends Controller
{
    // Mostrar lista de profesores
    public function index() {
        $estudiantes = Estudiantes::get();
        $profesores=Profesor::get(); // 🔥 agregamos los profesor
        return view('estudiantes')->with(compact('estudiantes','profesores'));
    }

    // Crear un nuevo estudiante
    public function create(Request $request) {
        Estudiantes::create([
            "id_profesor" => $request->id_profesor,
            "nombre" => $request->nombre,
            "apellido" => $request->apellido,
            "documento" => $request->documento,
            "fecha_nacimiento" => $request->fecha_nacimiento,
            "genero" => $request->genero,
            "nombre_responsable" => $request->nombre_responsable,
            "telefono_responsable" => $request->telefono_responsable,
            "email_responsable" => $request->email_responsable,
            "parentesco_responsable" => $request->parentesco_responsable,
            "direccion" => $request->ubicacion,
            "grado" => $request->grado,
            "seccion" => $request->seccion,
            "fecha_inscripcion" => $request->fecha_inscripcion,
            "activo" => $request->activo ?? true
        ]);
            

        return back()->with('success','Estudiante creado correctamente');
    }

    // Actualizar un Estudiante
    public function update(Request $request) {
        Estudiantes::where('id', $request->id)->update([
            "id_escuela" => $request->id_escuela,
            "id_Profesor" => $request->id_Profesor,
            "nombre" => $request->nombre,
            "apellido" => $request->apellido,
            "documento" => $request->documento,
            "fecha_nacimiento" => $request->fecha_nacimiento,
            "genero" => $request->genero,
            "nombre_responsable" => $request->nombre_responsable,
            "telefono_responsable" => $request->telefono_responsable,
            "email_responable" => $request->email,
            "parentesco_responsable" => $request->parentesco_responsable,
            "direccion" => $request->ubicacion,
            "grado" => $request->grado,
            "seccion" => $request->seccion,
            "fecha_inscripcion" => $request->fecha_inscripcion,
            "activo" => $request->activo ?? true
        ]);
            

        return back()->with('success','Estudiante actualizado correctamente');
    }

    // Eliminar un Estudiante
    public function delete(Request $request) {
        Estudiantes::where('id', $request->id)->delete();
        return back()->with('warning','Estudiante eliminado correctamente');
    }
}
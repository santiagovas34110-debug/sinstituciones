<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Escuelas;
use App\Imports\EstudiantesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\imports\ProfesoresImport;
use App\Models\Experiencia;

class ChecklistController extends Controller
{
    // === PANEL GENERAL ===
    public function panel()
    {
        // Cargar todas las escuelas con su checklist
        $escuelas = Escuelas::with('checklists')->get();

        // Calcular el estado de cada momento
        foreach ($escuelas as $escuela) {
            $check = $escuela->checklists->first();

            $escuela->conexion = $check && $check->fecha_agendamiento && $check->documento_estudiantes && $check->documento_docentes;
            $escuela->experiencia = $check && $check->estudiantes_asistieron && $check->docentes_asistieron;
            $escuela->reflexion = $check && $check->documento_reflexion;
        }

        return view('checklist.panel', compact('escuelas'));
    }

    // === CHECKLIST INDIVIDUAL POR ESCUELA ===
    public function show($id)
    {
        $escuela = Escuelas::findOrFail($id);
        $checklist = Checklist::firstOrCreate(['id_escuela' => $id]);

       return view('checklist.index', [
        'escuela' => $escuela,
        'checklist' => $checklist,
        'id_escuela' => $escuela->id, 
        ]);
    }

    // === MOMENTO 1: CONEXIÓN ===
    public function storeConexion(Request $request, $id)
    {
        $request->validate([
            'fecha_preconexion' => 'date',
            'fecha_agendamiento' => 'required|date',
            'documento_estudiantes' => 'required|file',
            'documento_docentes' => 'required|file',
        ]);

         // Crear o recuperar checklist
    $checklist = Checklist::firstOrCreate(['id_escuela' => $id]);

    // Importar archivo de estudiantes
    if ($request->hasFile('documento_estudiantes')) {
        $archivoEstudiantes = $request->file('documento_estudiantes');
        Excel::import(new EstudiantesImport($id), $archivoEstudiantes);
        $checklist->documento_estudiantes = $archivoEstudiantes->store('documentos', 'public');
    }

    if ($request->hasFile('documento_docentes')) {
        $archivoProfesores = $request->file('documento_docentes');
        Excel::import(new ProfesoresImport($id), $archivoProfesores);
        $checklist->documento_docentes = $archivoProfesores->store('documentos', 'public');
    }

    // Guardar datos
    $checklist->fecha_preconexion = $request->fecha_preconexion;
    $checklist->fecha_agendamiento = $request->fecha_agendamiento;

    if ($request->hasFile('documento_docentes')) {
        $checklist->documento_docentes = $request->file('documento_docentes')->store('documentos', 'public');
    }

    $checklist->save();

    return back()->with('success', '✅ Conexión guardada correctamente.');
}
    // Actualizar momento 1 (Conexión)
public function updateConexion(Request $request, $id)
{
    $request->validate([
        'fecha_preconexion' => 'required|date',
        'fecha_agendamiento' => 'required|date',
        'documento_estudiantes' => 'nullable|file',
        'documento_docentes' => 'nullable|file',
    ]);

    $checklist = Checklist::where('id_escuela', $id)->firstOrFail();

    // Actualizar fechas
    $checklist->fecha_preconexion = $request->fecha_preconexion;
    $checklist->fecha_agendamiento = $request->fecha_agendamiento;

    // Actualizar archivos si se envían
    if ($request->hasFile('documento_estudiantes')) {
        $checklist->documento_estudiantes = $request->file('documento_estudiantes')->store('documentos', 'public');
    }

    if ($request->hasFile('documento_docentes')) {
        $checklist->documento_docentes = $request->file('documento_docentes')->store('documentos', 'public');
    }

    $checklist->save();

    return back()->with('success', '✅ Conexión actualizada correctamente.');
}

   // === MOMENTO 2: EXPERIENCIA ===
// === MOMENTO 2: EXPERIENCIA ===
public function updateExperiencia(Request $request, $idEscuela)
{
    $checklist = Checklist::where('id_escuela', $idEscuela)->firstOrFail();

    // Recoger las fechas del formulario (fecha_experiencia_1 ... fecha_experiencia_5)
    $fechas = [];
    for ($i = 1; $i <= 5; $i++) {
        $campo = 'fecha_experiencia_' . $i;
        if ($request->filled($campo)) {
            $fechas[$i] = $request->input($campo);
            $checklist->$campo = $fechas[$i]; // actualizar checklist
        } else {
            $checklist->$campo = null; // limpiar si está vacío
        }
    }
    $checklist->save();

    // Crear Experiencias y EstudianteExperiencia para cada fecha
    foreach ($fechas as $num => $fecha) {
        // Crear o encontrar la experiencia (guardamos id_checklist)
        $experiencia = Experiencia::firstOrCreate(
            ['id_escuela' => $idEscuela, 'fecha_experiencia' => $fecha],
            [
                'id_checklist' => $checklist->id,
                'tipo_documento' => null,
                'documento' => null,
                'primer_nombre' => null,
                'segundo_nombre' => null,
                'primer_apellido' => null,
                'segundo_apellido' => null,
                'grado' => null,
                'asistencia' => false
            ]
        );

        // Clonar estudiantes a EstudianteExperiencia si no existen (AHORA con id_experiencia y fecha_asistencia)
        $alumnos = \App\Models\Estudiantes::where('id_escuela', $idEscuela)->get();
        foreach ($alumnos as $alumno) {
            \App\Models\EstudianteExperiencia::firstOrCreate(
                [
                    'id_experiencia' => $experiencia->id,
                    'id_estudiante' => $alumno->id
                ],
                [
                    'id_escuela' => $idEscuela,
                    'asistencia' => false,
                    'fecha_asistencia' => null
                ]
            );
        }
    }

    return redirect()
        ->route('checklist.show', $idEscuela)
        ->with('success', '✅ Fechas y experiencias actualizadas correctamente.');
}


// === BORRAR UNA FECHA ESPECÍFICA ===
public function deleteFechaExperiencia($id, $num)
{
    $checklist = Checklist::where('id_escuela', $id)->firstOrFail();

    if ($num >= 1 && $num <= 5) {
        $campo = 'fecha_experiencia_' . $num;
        $checklist->$campo = null;
        $checklist->save();

        return back()->with('warning', "⚠️ Fecha de experiencia {$num} eliminada correctamente.");
    }

    return back()->with('error', '❌ Fecha inválida.');
}
    
    // === MOMENTO 3: REFLEXIÓN ===
    public function storeReflexion(Request $request, $id)
    {
        $checklist = Checklist::where('id_escuela', $id)->firstOrFail();

        if (!$checklist->estudiantes_asistieron) {
            return back()->with('error', '⚠️ Primero debes completar el momento Experiencia.');
        }

        $request->validate([
            'documento_reflexion' => 'required|file',
        ]);

        $checklist->documento_reflexion = $request->file('documento_reflexion')->store('reflexiones', 'public');
        $checklist->save();

        return back()->with('success', '✅ Reflexión guardada correctamente.');
    }
    // Actualizar momento 3 (Reflexión)
    public function updateReflexion(Request $request, $id)
    {   
        $checklist = Checklist::where('id_escuela', $id)->firstOrFail();
        if (!$checklist->estudiantes_asistieron) {
            return back()->with('error', '⚠️ Primero debes completar el momento Experiencia.');
        }
        $request->validate([
            'documento_reflexion' => 'nullable|file',
        ]);
        if ($request->hasFile('documento_reflexion')) {
            $checklist->documento_reflexion = $request->file('documento_reflexion')->store('reflexiones', 'public');
            $checklist->save();
        }
        return back()->with('success', '✅ Reflexión actualizada correctamente.');
    } 
    // Eliminar archivo del momento 3 (Reflexión)
    public function deleteArchivoReflexion(Request $request, $id)
    {
        $checklist = Checklist::where('id_escuela', $id)->firstOrFail();  
        $checklist->documento_reflexion = null;
        $checklist->save();
        return back()->with('warning', '⚠️ Archivo de Reflexión eliminado correctamente.'); 
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Escuelas;
use App\Imports\EstudiantesImport;
use Maatwebsite\Excel\Facades\Excel;

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

        return view('checklist.index', compact('escuela', 'checklist'));
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
      // Eliminar archivos del momento 1 (Conexión)
public function deleteArchivoConexion($id, $tipo)
{
    $checklist = Checklist::where('id_escuela', $id)->firstOrFail();

    if ($tipo === 'estudiantes') {
        if ($checklist->documento_estudiantes) {
            \Storage::disk('public')->delete($checklist->documento_estudiantes);
        }
        $checklist->documento_estudiantes = null;
    } elseif ($tipo === 'docentes') {
        if ($checklist->documento_docentes) {
            \Storage::disk('public')->delete($checklist->documento_docentes);
        }
        $checklist->documento_docentes = null;
    }

    $checklist->save();

    return back()->with('warning', '⚠️ Archivo eliminado correctamente.');
}

    // === MOMENTO 2: EXPERIENCIA ===
    public function storeExperiencia(Request $request, $id)
    {
        $checklist = Checklist::where('id_escuela', $id)->firstOrFail();

        if (!$checklist->fecha_agendamiento) {
            return back()->with('error', '⚠️ Primero debes completar el momento Conexión.');
        }

        $request->validate([
            'estudiantes_asistieron' => 'required|integer|min:0',
            'docentes_asistieron' => 'required|integer|min:0',
        ]);

        $checklist->update([
            'estudiantes_asistieron' => $request->estudiantes_asistieron,
            'docentes_asistieron' => $request->docentes_asistieron,
        ]);

        return back()->with('success', '✅ Experiencia guardada correctamente.');
    }

    // Actualizar momento 2 (Experiencia)
    public function updateExperiencia(Request $request, $id)
    {
        $checklist = Checklist::where('id_escuela', $id)->firstOrFail();
        if (!$checklist->fecha_agendamiento) {
            return back()->with('error', '⚠️ Primero debes completar el momento Conexión.');
        }
        $request->validate([
            'estudiantes_asistieron' => 'required|integer|min:0',
            'docentes_asistieron' => 'required|integer|min:0',
        ]);
        $checklist->update([
            'estudiantes_asistieron' => $request->estudiantes_asistieron,
            'docentes_asistieron' => $request->docentes_asistieron,
        ]);
        return back()->with('success', '✅ Experiencia actualizada correctamente.');
    }

    // Eliminar datos del momento 2 (Experiencia)
    public function deleteExperiencia(Request $request, $id)
    {
        $checklist = Checklist::where('id_escuela', $id)->firstOrFail();  
        $checklist->estudiantes_asistieron = null;
        $checklist->docentes_asistieron = null;
        $checklist->save();
        return back()->with('warning', '⚠️ Datos de Experiencia eliminados correctamente.');
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
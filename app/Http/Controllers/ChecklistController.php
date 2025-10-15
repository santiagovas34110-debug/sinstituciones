<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Escuelas;
use App\Imports\EstudiantesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\imports\ProfesoresImport;

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
public function updateExperiencia(Request $request, $id)
{
    $checklist = Checklist::where('id_escuela', $id)->firstOrFail();

    // Asegura que el momento "Conexión" esté completado antes
    if (!$checklist->fecha_agendamiento) {
        return back()->with('error', '⚠️ Primero debes completar el momento Conexión.');
    }

    // Validación flexible: solo valida lo que llega
    $rules = [
        'estudiantes_asistieron' => 'nullable|integer|min:0',
        'docentes_asistieron' => 'nullable|integer|min:0',
        'fecha_experiencia_1' => 'nullable|date',
        'fecha_experiencia_2' => 'nullable|date',
        'fecha_experiencia_3' => 'nullable|date',
        'fecha_experiencia_4' => 'nullable|date',
        'fecha_experiencia_5' => 'nullable|date',
    ];

    $validated = $request->validate($rules);

    // Filtrar solo los campos con valor, para no sobrescribir con null
    $data = array_filter($validated, fn($value) => !is_null($value));

    // Si no hay ningún campo válido, no hacemos nada
    if (empty($data)) {
        return back()->with('warning', '⚠️ No se detectaron cambios para guardar.');
    }

    // Guardar solo lo que llega
    $checklist->update($data);

    return back()->with('success', '✅ Experiencia guardada correctamente.');
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
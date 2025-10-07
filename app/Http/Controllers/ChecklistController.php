<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Escuelas;

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
            'fecha_agendamiento' => 'required|date',
            'documento_estudiantes' => 'required|file',
            'documento_docentes' => 'required|file',
        ]);

        $checklist = Checklist::firstOrCreate(['id_escuela' => $id]);

        // Guardar archivos
        $checklist->fecha_agendamiento = $request->fecha_agendamiento;
        $checklist->documento_estudiantes = $request->file('documento_estudiantes')->store('documentos', 'public');
        $checklist->documento_docentes = $request->file('documento_docentes')->store('documentos', 'public');
        $checklist->save();

        return back()->with('success', '✅ Conexión guardada correctamente.');
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
}
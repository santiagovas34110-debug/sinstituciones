<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escuelas;
use App\Models\Checklist;
use App\Models\Estudiantes;
use App\Models\Experiencia;
use App\Models\EstudianteExperiencia;

class ExperienciaController extends Controller
{
    // Mostrar lista de experiencias disponibles (solo las fechas)
    public function show($idEscuela)
    {
        $escuela = Escuelas::findOrFail($idEscuela);

        // Traer experiencias para este colegio
        $experiencias = Experiencia::where('id_escuela', $idEscuela)->get();

        // SIN SINCRONIZAR AÚN. La sincronización se hace al gestionar asistencia,
        // porque depende de una fecha seleccionada.

        return view('experiencias.show', compact('escuela', 'experiencias'));
    }

    // Gestionar asistencias por fecha
    public function gestionar(Request $request, $idEscuela)
    {
        $escuela = Escuelas::findOrFail($idEscuela);
        $checklist = Checklist::where('id_escuela', $idEscuela)->firstOrFail();

        // Paso 1: Construimos el listado de fechas diligenciadas en checklist
        $fechasExperiencia = [];
        for ($i = 1; $i <= 5; $i++) {
            $campo = 'fecha_asistencia' . $i;
            if (!empty($checklist->$campo)) {
                $fechasExperiencia[] = $checklist->$campo;
            }
        }

        // Si NO HAY FECHAS diligenciadas, retornamos con mensaje
        if (empty($fechasExperiencia)) {
            return redirect()
                ->back()
                ->with('error', '⚠️ Deben existir fechas diligenciadas en "Fechas de la experiencia" antes de gestionar asistencias.');
        }

        // Paso 2: ver cuál fecha seleccionó el usuario
        $fechaSeleccionada = $request->input('fecha', $fechasExperiencia[0] ?? null);

        // Paso 3: buscar la experiencia asociada a esa fecha
        $experiencia = Experiencia::where('id_escuela', $idEscuela)
            ->where('fecha_asistencia', $fechaSeleccionada)
            ->first();

        // Si no existe aún (ej. primera vez que registra asistencia en esa fecha), crearla
        if (!$experiencia) {
            $experiencia = Experiencia::create([
                'id_escuela' => $idEscuela,
                'fecha_asistencia' => $fechaSeleccionada
            ]);
        }

        // Paso 4: Sincronizar estudiantes con esa experiencia
        $alumnos = Estudiantes::where('id_escuela', $idEscuela)->get();
        foreach ($alumnos as $alumno) {
            EstudianteExperiencia::firstOrCreate(
                [
                    'id_experiencia' => $experiencia->id,
                    'id_estudiante'  => $alumno->id
                ],
                [
                    'id_escuela'      => $idEscuela,
                    'asistencia'      => false,
                    'fecha_asistencia'=> null
                ]
            );
        }

        // Paso 5: Traer registros para mostrarlos en vista
        $estudiantesPendientes = EstudianteExperiencia::with('estudiante')
            ->where('id_escuela', $idEscuela)
            ->where('id_experiencia', $experiencia->id)
            ->get();

        return view('experiencias.asistencias_guardadas', compact(
            'escuela',
            'estudiantesPendientes',
            'fechaSeleccionada',
            'fechasExperiencia',
            'experiencia'
        ));
    }

    // Guardar asistencia masiva
    public function storeAsistencia(Request $request, $idEscuela)
    {
        $idExperiencia = $request->input('id_experiencia');
        $fecha = $request->input('fecha');

        $estudiantes = $request->input('estudiantes', []);

        foreach ($estudiantes as $registroId => $data) {
            $registro = EstudianteExperiencia::where('id', $registroId)
                ->where('id_escuela', $idEscuela)
                ->first();

            if (!$registro) continue;

            $asistio = isset($data['asistencia']) ? 1 : 0;

            $registro->asistencia = $asistio;
            $registro->fecha_asistencia = $asistio ? $fecha : null;
            $registro->id_experiencia = $idExperiencia;
            $registro->save();
        }

        // Actualizamos contador en checklist (según escuela)
        $contadorAsistieron = EstudianteExperiencia::where('id_escuela', $idEscuela)
            ->where('asistencia', 1)
            ->count();

        $checklist = Checklist::where('id_escuela', $idEscuela)->first();
        if ($checklist) {
            $checklist->estudiantes_asistieron = $contadorAsistieron;
            $checklist->save();
        }

        return redirect()
            ->route('experiencias.asistenciasGuardadas', ['idEscuela' => $idEscuela, 'fecha' => $fecha])
            ->with('success', '✅ Asistencias guardadas correctamente.');
    }

    // Crear estudiante desde experiencia
    public function crearEstudiante(Request $request, $idEscuela)
    {
        $request->validate([
            'tipo_documento' => 'required',
            'documento'      => 'required|unique:estudiantes,documento',
            'nombre'  => 'required',
            'apellido'=> 'required',
            'grado'   => 'nullable',
            
        ]);

        $estudiante = Estudiantes::create([
            'tipo_documento'  => $request->tipo_documento,
            'documento'       => $request->documento,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'grado'           => $request->grado ?? '',
            'id_escuela'      => $idEscuela,
        ]);

        // Clonarlo a TODAS LAS EXPERIENCIAS YA CREADAS
        $experiencias = Experiencia::where('id_escuela', $idEscuela)->get();
        foreach ($experiencias as $exp) {
            EstudianteExperiencia::firstOrCreate(
                [
                    'id_experiencia' => $exp->id,
                    'id_estudiante'  => $estudiante->id
                ],
                [
                    'id_escuela'     => $idEscuela,
                    'asistencia'     => false,
                    'fecha_asistencia'=> null
                ]
            );
        }

        return back()->with('success', '✅ Estudiante creado correctamente.');
    }
}
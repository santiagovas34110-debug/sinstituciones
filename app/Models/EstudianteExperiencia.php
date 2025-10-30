<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteExperiencia extends Model
{
    use HasFactory;

    protected $table = 'estudiante_experiencia';

    protected $fillable = [
        'id_escuela',
        'id_experiencia',
        'id_estudiante',
        'nombre',
        'apellido',
        'tipo_documento',
        'documento',
        'asistencia',
        'fecha_asistencia', // <-- CORREGIDO (antes: fecha_experiencia)
    ];

    public function experiencia()
    {
        return $this->belongsTo(Experiencia::class, 'id_experiencia');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiantes::class, 'id_estudiante');
    }
}
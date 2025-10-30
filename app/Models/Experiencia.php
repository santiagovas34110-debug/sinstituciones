<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    use HasFactory;

    protected $table = 'checklists';

    protected $fillable = [
        'id_checklist',
        'id_escuela',
        'tipo_documento',
        'documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'grado',
        'asistencia',
        'fecha_asistencia',
    ];

    public function escuela()
    {
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id');
    }

    public function estudiantes()
    {
    return $this->belongsToMany(Estudiantes::class, 'estudiante_experiencia', 'id_experiencia', 'id_estudiante')
         ->withPivot('asistencia', 'fecha_experiencia')
         ->withTimestamps();
    }

    public function checklist()
    {
    return $this->belongsTo(Checklist::class, 'id_checklist', 'id');
    }

}

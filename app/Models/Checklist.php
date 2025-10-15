<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_escuela',
        'fecha_preconexion',
        'fecha_agendamiento',
        'documento_estudiantes',
        'documento_docentes',
        'estudiantes_asistieron',
        'docentes_asistieron',
        'documento_reflexion',
        'fecha_experiencia_1',
        'fecha_experiencia_2',
        'fecha_experiencia_3',
        'fecha_experiencia_4',
        'fecha_experiencia_5',
    ];

    public function escuela()
    {
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id');
    }
}

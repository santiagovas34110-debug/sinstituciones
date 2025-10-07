<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_escuela',
        'fecha_agendamiento',
        'documento_estudiantes',
        'documento_docentes',
        'estudiantes_asistieron',
        'docentes_asistieron',
        'documento_reflexion',
    ];

    public function escuela()
    {
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id');
    }
}

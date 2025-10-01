<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profesor;
use App\Models\Grupo;


class Estudiantes extends Model
{
    use HasFactory;
    protected $table ="estudiantes";

    protected $fillable = [
        "id_profesor",
        "nombre",
        "apellido",
        "documento",
        "fecha_nacimiento",
        "genero",
        "nombre_responsable",
        "telefono_responsable",
        "email_responsable",
        "parentesco_responsable",
        "direccion",
        "id_grado",
        "seccion",
        "fecha_inscripcion",
        "activo"
    ];
    public function profesor()
    {
    return $this->belongsTo(Profesor::class, 'id_profesor');
    }

    public function grupo(){
        return $this->benlongsTo(Grupo::class, 'id_grado');
    }
}

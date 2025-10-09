<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Profesor extends Model
{
    use HasFactory;
    protected $table ="profesores";

    protected $fillable = [
        "id_escuela",
        "nombre",
        "apellido",
        "documento",
        "fecha_nacimiento",
        "genero",
        "email",
        "telefono",
        "direccion",
        "titulo",
        "especialidad",
        "fecha_ingreso",
        "salario",
        "grado",
        "activo"
    ];
    public function escuela()
    {
    return $this->belongsTo(Escuelas::class, 'id_escuela');
    }

    /*
    public function escuela()
    {
    return $this->hasOne(Escuelas::class, 'id_escuela');
    }
    */
}

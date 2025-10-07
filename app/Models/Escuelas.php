<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profesor;

class Escuelas extends Model

{
    use HasFactory;

    protected $table ="escuelas";

    protected $fillable = [
           'nombre',
           'ubicacion',
           'contacto_nombre',
           'contacto_rol',
           'contacto_telefono',
           'contacto_email',
           'contacto_documento',
           'contacto_tipo_documento',
           'codigo_dane',
           'nit'
    ];
    public function profesores(){
        return $this->hasMany(Profesor::class,'id_escuela','id');
    }
    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'id_escuela', 'id');
    }

}

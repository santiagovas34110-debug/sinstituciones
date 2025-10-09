<?php

namespace App\Imports;

use App\Models\Profesor;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;


class ProfesoresImport implements ToModel
{
    private $id_escuela = null;

    public function __construct($id_escuela){
        $this->id_escuela = $id_escuela;
    }

    public function model(array $row)
    {

        if(is_numeric($row[1])){ // si el document es numerico

            return new Profesor([
                "nombre" => $row[0],
                "documento" => $row[1],
                "grado" => $row[2],
                "email" => $row[3],
                "telefono" => trim($row[4]),
                "id_escuela" => $this->id_escuela

            ]);
        }
    }

   
}
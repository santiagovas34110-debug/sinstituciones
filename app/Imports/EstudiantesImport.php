<?php

namespace App\Imports;

use App\Models\Estudiantes;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class EstudiantesImport implements ToModel
{
    private $id_escuela = null;

    public function __construct($id_escuela){
        $this->id_escuela = $id_escuela;
    }

    public function model(array $row)
    {

        if(is_numeric($row[1])){ // si el document es numerico

            return new Estudiantes([
                "tipo_documento" => $row[0],
                "documento" => $row[1],
                "nombre" => trim($row[2]." ".$row[3]),
                "apellido" => trim($row[4]." ".$row[5]),
                "genero" => $row[6],
                "fecha_nacimiento" => $this->transformDate($row[7] ?? "1900-01-01"),
                "grado" => $row[12],
                "id_escuela" => $this->id_escuela

            ]);
        }
    }

    private function transformDate(string $value, string $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format($format);
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, $value);
        }
    }
}
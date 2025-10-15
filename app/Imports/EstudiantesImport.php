<?php

namespace App\Imports;

use App\Models\Estudiantes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EstudiantesImport implements ToModel, WithHeadingRow
{
    protected $id_escuela;

    public function __construct($id_escuela)
    {
        $this->id_escuela = $id_escuela;
    }

    public function model(array $row)
    {
        // 1) Normalizar claves: quitar tildes, espacios, símbolos -> a_lower_case_snake
        $cleanRow = [];
        foreach ($row as $origKey => $value) {
            $k = $this->normalizeKey($origKey);
            $cleanRow[$k] = $value;
        }

        // (Opcional) inspección rápida: descomenta para ver exactamente las claves del archivo
        // dd(array_keys($cleanRow), $cleanRow);

        // 2) Limpiar documento: solo números y letras
        $documento = preg_replace('/[^0-9A-Za-z]/', '', $cleanRow['n_de_documento'] ?? '');

        // 3) Evitar filas vacías
        if (empty($documento) && empty($cleanRow['primer_nombre'] ?? '') && empty($cleanRow['primer_apellido'] ?? '')) {
            return null;
        }

        // 4) Nombres y apellidos
        $nombre = trim(($cleanRow['primer_nombre'] ?? '') . ' ' . ($cleanRow['segundo_o_demas_nombres'] ?? ''));
        $apellido = trim(($cleanRow['primer_apellido'] ?? '') . ' ' . ($cleanRow['segundo_apellido'] ?? ''));

        // 5) Buscar campo grado / grupo dinámicamente
        $gradoKey = $this->findKeyContains($cleanRow, ['grado', 'grupo']);
        $grado = $gradoKey ? ($cleanRow[$gradoKey] ?? '') : '';

        // 6) Crear el modelo
        return new Estudiantes([
            "tipo_documento"   => $cleanRow['tipo_doc'] ?? $cleanRow['tipo_documento'] ?? "",
            "documento"        => $documento,
            "nombre"           => $nombre,
            "apellido"         => $apellido,
            "genero"           => $cleanRow['genero'] ?? "",
            "fecha_nacimiento" => $this->transformDate($cleanRow['fecha_de_nacimiento'] ?? $cleanRow['fecha_nacimiento'] ?? "1900-01-01"),
            "grado"            => $grado,
            "id_escuela"       => $this->id_escuela,
        ]);
    }

    // Normaliza una clave: quita tildes, convierte a ascii, reemplaza no alfanum por guion bajo, trim, lowercase
    private function normalizeKey($key)
    {
        // convertir a string, trim
        $k = trim((string) $key);

        // quitar BOM si existe
        $k = preg_replace('/\x{FEFF}/u', '', $k);

        // pasar a ascii (quitar tildes)
        $k = iconv('UTF-8', 'ASCII//TRANSLIT', $k) ?: $k;

        // reemplazar cualquier caracter no alfanumérico por guion bajo
        $k = preg_replace('/[^A-Za-z0-9]+/', '_', $k);

        // eliminar guiones bajos duplicados y posibles _ al inicio/final
        $k = preg_replace('/_+/', '_', $k);
        $k = trim($k, '_');

        // minusculas
        $k = strtolower($k);

        return $k;
    }

    // Busca la primera clave del array que contenga cualquiera de los fragmentos dados
    private function findKeyContains(array $row, array $fragments)
    {
        foreach ($row as $key => $val) {
            foreach ($fragments as $f) {
                if (stripos($key, $f) !== false) {
                    return $key;
                }
            }
        }
        return null;
    }

    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            } else {
                return Carbon::parse($value)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return Carbon::parse('1900-01-01')->format('Y-m-d');
        }
    }
}

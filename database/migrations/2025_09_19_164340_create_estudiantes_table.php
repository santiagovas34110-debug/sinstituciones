<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('estudiantes', function (Blueprint $table) {
    $table->id();

    $table->integer('id_profesor')->nullable();// ID del profesor asignado

    // Información personal
    $table->string('nombre')->nullable();                  // Nombre del profesor
    $table->string('apellido')->nullable();                // Apellido
    $table->string('documento')->unique();     // Cédula o documento de identidad
    $table->date('fecha_nacimiento')->nullable(); // Fecha de nacimiento
    $table->string('genero', 10)->nullable();  // Masculino, Femenino, Otro

    // Contacto responsable
    $table->string('nombre_responsable')->nullable();      // Nombre del responsable
    $table->string('telefono_responsable')->nullable();    // Teléfono del responsable
    $table->string('email_responsable')->unique();         // Correo electrónico
    $table->string('parentesco_responsable')->nullable();  // Parentesco con el estudiante
    $table->string('direccion')->nullable();   // Dirección

    // Información académica
    $table->string('grado')->nullable();                   // Grado o curso
    $table->string('seccion')->nullable();                 // Sección o grupo
    $table->date('fecha_inscripcion')->nullable(); // Fecha de inscripción
       
    // Estado
    $table->boolean('activo')->default(true);  // Activo o dado de baja


    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};

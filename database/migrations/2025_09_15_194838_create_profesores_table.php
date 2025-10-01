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
      Schema::create('profesores', function (Blueprint $table) {
    $table->id();

    // Información personal
    $table->string('nombre')->nullable();                  // Nombre del profesor
    $table->string('apellido')->nullable();                // Apellido
    $table->string('documento')->unique();     // Cédula o documento de identidad
    $table->date('fecha_nacimiento')->nullable(); // Fecha de nacimiento
    $table->string('genero', 10)->nullable();  // Masculino, Femenino, Otro

    // Contacto
    $table->string('email')->unique();         // Correo electrónico
    $table->string('telefono')->nullable();    // Teléfono
    $table->string('direccion')->nullable();   // Dirección

    // Información laboral
    $table->string('titulo')->nullable();      // Título universitario o certificación
    $table->string('especialidad')->nullable();// Ej: Matemáticas, Física
    $table->date('fecha_ingreso')->nullable(); // Cuándo empezó a trabajar
    $table->decimal('salario', 10, 2)->nullable(); // Salario (si se maneja)

    // Estado
    $table->boolean('activo')->default(true);  // Activo o dado de baja

    // 🔑 Relación con Escuelas
    $table->integer('id_escuela')->nullable();  
  

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};

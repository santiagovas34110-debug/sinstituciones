<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();

            // 🔗 Relación con Escuelas
            $table->foreignId('id_escuela')->constrained('escuelas')->onDelete('cascade');

            // Momento Conexión
            $table->date('fecha_agendamiento')->nullable();
            $table->string('documento_estudiantes')->nullable();
            $table->string('documento_docentes')->nullable();

            // Momento Experiencia
            $table->integer('estudiantes_reportados')->nullable();
            $table->integer('docentes_reportados')->nullable();
            $table->integer('estudiantes_asistieron')->nullable();
            $table->integer('docentes_asistieron')->nullable();

            // Momento Reflexión
            $table->string('documento_reflexion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
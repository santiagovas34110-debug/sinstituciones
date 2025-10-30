<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiante_experiencia', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_experiencia');
            $table->unsignedBigInteger('id_estudiante');

            $table->boolean('asistencia')->default(false);
            $table->date('fecha_asistencia')->nullable();

            $table->timestamps();

            // FOREIGN KEYS
            $table->foreign('id_experiencia')->references('id')->on('experiencias')->onDelete('cascade');
            $table->foreign('id_estudiante')->references('id')->on('estudiantes')->onDelete('cascade');

            // Evitar duplicados (UN ESTUDIANTE solo 1 vez por experiencia)
            $table->unique(['id_experiencia', 'id_estudiante']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiante_experiencia');
    }
};
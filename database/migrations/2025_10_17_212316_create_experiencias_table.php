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
        Schema::create('checklist_estudiante', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('checklist_id');
    $table->unsignedBigInteger('estudiante_id');
    $table->boolean('asistencia')->default(false);

    $table->foreign('checklist_id')->references('id')->on('checklists')->onDelete('cascade');
    $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');

    $table->timestamps();
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiencias');
    }
};

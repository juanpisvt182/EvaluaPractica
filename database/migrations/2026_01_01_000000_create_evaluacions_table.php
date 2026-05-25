<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID del Instructor
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->integer('tiempo_limite'); // Tiempo en minutos
            $table->string('estado')->default('Activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluacions');
    }
};

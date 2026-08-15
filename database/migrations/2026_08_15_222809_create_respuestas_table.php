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
    Schema::create('respuestas', function (Blueprint $table) {
        $table->id();

        // Intento al que pertenece la respuesta
        $table->foreignId('intento_id')
            ->constrained('intentos')
            ->cascadeOnDelete();

        // Pregunta respondida
        $table->foreignId('pregunta_id')
            ->constrained('preguntas')
            ->cascadeOnDelete();

        // Opción seleccionada por el aprendiz
        $table->foreignId('opcion_id')
            ->constrained('opciones')
            ->cascadeOnDelete();

        // Guardamos si acertó o no
        $table->boolean('es_correcta')->default(false);

        $table->timestamps();

        // Evita responder dos veces la misma pregunta dentro del mismo intento
        $table->unique(['intento_id', 'pregunta_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};

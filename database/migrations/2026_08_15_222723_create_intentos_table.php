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
    Schema::create('intentos', function (Blueprint $table) {
        $table->id();

        // Aprendiz que presenta la evaluación
        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        // Evaluación que está presentando
        $table->foreignId('evaluacion_id')
            ->constrained('evaluacions')
            ->cascadeOnDelete();

        // Resultado
        $table->integer('total_preguntas')->default(0);
        $table->integer('respuestas_correctas')->default(0);
        $table->decimal('puntaje', 5, 2)->default(0);

        // Estado del intento
        $table->string('estado')->default('En progreso');

        $table->timestamp('finalizado_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intentos');
    }
};

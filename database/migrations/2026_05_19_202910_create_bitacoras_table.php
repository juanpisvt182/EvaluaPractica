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
       Schema::create('bitacoras', function (Blueprint $table) {
    $table->id();

    // Relación: la bitácora pertenece a un usuario (aprendiz)
    $table->foreignId('user_id')->constrained()->onDelete('cascade');

    // Datos principales
    $table->string('numero')->unique(); // Ej: BIT-023
    $table->date('fecha');
    $table->enum('estado', ['Borrador', 'Enviado', 'Aprobado'])->default('Borrador');

    // Opcional: archivo adjunto (PDF/Word)
    $table->string('archivo_path')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};

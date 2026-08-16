<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bitacoras', function (Blueprint $table) {

            // Grupo/Materia al que pertenece la bitácora.
            $table->foreignId('grupo_id')
                ->nullable()
                ->constrained('grupos')
                ->nullOnDelete();

            // Usuario que realizó la revisión.
            $table->foreignId('revisor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Comentario del docente.
            $table->text('retroalimentacion')
                ->nullable();

            // Fecha en que fue revisada.
            $table->timestamp('revisado_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bitacoras', function (Blueprint $table) {

            $table->dropForeign(['grupo_id']);
            $table->dropForeign(['revisor_id']);

            $table->dropColumn([
                'grupo_id',
                'revisor_id',
                'retroalimentacion',
                'revisado_at',
            ]);
        });
    }
};
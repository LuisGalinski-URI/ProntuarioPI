<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas_final', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->constrained('pacientes_final')
                ->cascadeOnDelete();

            $table->date('data_consulta');
            $table->time('horario_consulta');

            $table->json('sintomas_id');
            $table->boolean('possui_doencas_cronicas')->nullable();

            $table->unsignedInteger('intensidade_id');
            $table->unsignedInteger('periodo_id');

            $table->string('status')->default('Aguardando');
            $table->unsignedInteger('prioridade')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas_final');
    }
};

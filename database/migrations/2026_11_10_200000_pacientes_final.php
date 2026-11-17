<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('consultas');
        Schema::dropIfExists('pacientes');

        Schema::create('pacientes_final', function (Blueprint $table) {
            $table->id();

            $table->string('nome_completo')->nullable();
            $table->string('cpf', 14)->unique();

            $table->unsignedInteger('sexo_id');

            $table->string('nacionalidade', 100)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('email')->nullable();

            $table->date('data_nascimento')->nullable();

            $table->string('cep', 9)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero_endereco', 20)->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('complemento_endereco')->nullable();

            $table->unsignedInteger('numero_consultas')->default(0);
            $table->date('data_ultima_consulta')->nullable();

            $table->text('doencas_cronicas')->nullable();
            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes_final');
    }
};

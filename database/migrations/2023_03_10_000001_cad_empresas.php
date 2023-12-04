<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class cadEmpresas extends Migration
{

    public function up()
    {
        Schema::create('cad_empresas', function (Blueprint $table) {
            $table->id();
            $table->string('inscricao_estadual', 64)->nullable();
            $table->string('razao_social', 128);
            $table->string('cnpj', 128);
            $table->string('atividade_principal', 32)->nullable();
            $table->string('complemento', 128)->nullable();
            $table->string('data_situacao', 128)->nullable();
            $table->string('tipo', 128)->nullable();
            $table->string('uf', 8)->nullable();
            $table->string('telefone', 128)->nullable();
            $table->string('situacao', 128)->nullable();
            $table->string('bairro', 128)->nullable();
            $table->string('logradouro', 128)->nullable();
            $table->string('numero', 8)->nullable();
            $table->string('cep', 16)->nullable();
            $table->string('municipio', 128)->nullable();
            $table->string('porte', 64)->nullable();
            $table->string('abertura', 128)->nullable();
            $table->string('natureza_juridica', 128)->nullable();
            $table->string('fantasia', 128)->nullable();
            $table->string('ultima_atualizacao')->nullable();
            $table->string('status', 8)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('efr', 128)->nullable();
            $table->string('motivo_situacao', 128)->nullable();
            $table->string('situacao_especial', 128)->nullable();
            $table->string('data_situacao_especial')->nullable();
            $table->string('capital_social')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cad_empresas');
    }
}

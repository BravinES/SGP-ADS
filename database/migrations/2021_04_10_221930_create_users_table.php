<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**  Tabela de Usuários do sistema
         *  ------------------------------------------------------------
         * id()                 => Cria o ID do Usuário;
         * @name                => Nome do Usuário;
         * @email               => Email do Usuário;
         * @cpf                 => CPF do Usuário;
         * @username            => Nome de usuário | Fututamente usar para Logar;
         * @password            => Senha do Usuário;
         * @recover_password    => Hash para quando o usuário for revuperar senha;
         * @remember_token      => Token para a opção "lembrar do usuário" no login;
         * @photo               => Caminho (path) da pasta do usuário;
         * @phone               => Telefone do usuário;
         * @id_access_groups    => Id do principal Grupo de acesso do usuário (Taela: access_groups);
         * @id_user_states      => Id da tabela com o estado do usuário (Tabela: user_states)
         *                         (Ex.: 1 - ativo, 2 - inativo);
         * @email_verified_at   => Data em que o usuário vez a validação do seu email;
         * @updated_by          => Informa o ID do ultimo usuário que atualizou o sistema;
         * timestamps()         => Cria os campos create_at (Criado em ) e update_at (atualizado em) na table;
         * softDeletes()        => Indica se o campo esta deletado "virtualmente"e a data que em o registro
         *                         foi deletado "virtualmente"
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('default');
            $table->string('name', 256);
            $table->string('name_show', 128)->nullable();
            $table->string('email', 256)->unique();
            $table->string('cpf', 32)->unique()->nullable();
            $table->string('username', 128)->unique()->nullable();
            $table->string('password', 256);
            $table->string('recover_password', 128)->nullable();
            $table->string('photo', 128)->nullable();
            $table->string('phone', 16)->nullable();
            $table->string('access_token', 256)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('actions__config', function (Blueprint $table) {

            /**  Tabela de configuração de opções do sistema de ações
             *  ------------------------------------------------------------
             * id()                         => ID da Usuário;
             * @c_name                      => Nome da configuração;
             * @c_model                     => Informa se é um modelo | 0 = não, 1 = sim;
             * @user_id                     => Se model igual a 0, então o indica usuário que criou a configuração;
             * @c_board_name                => Nome que será exibido para o quadro;
             * @c_board_view_project_color  => Cor do Projeto | 0 = não, 1 = sim;
             * @c_board_view_percent_type   => Tipo em em que a procentagem será exibida | 0 = não exibe, 1 = Barra de porcentagem, 2 = porcentagem em background;
             * @c_task_name                 => Nome que será exibido para a tarefa;
             * @c_view_project_color        => Opção para o usuário escolher ver ou não as cores do projeto;
             * @c_task_status_name          => Cor para definir cada tipo de tarefa separadas por pontos e vírgula;
             * @c_board_view_project_color  => Cor nome para cada status da tarefa separadas por pontos e vírgula
             *                             Sendo eles (Não iniciado, Em andamento, Atrasado, Concluído, Cancelado);
             *
             * timestamps()                 => Cria os campos create_at (Criado em ) e update_at (atualizado em) na table;
             * softDeletes()                => Indica se o campo esta deletado "virtualmente"e a data que em o registro
             *                                 foi deletado "virtualmente"
             */

            $table->id();
            $table->string('c_name', 32);
            $table->integer('c_model')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('c_board_name', 128)->default('Projeto');
            $table->integer('c_board_view_project_color')->default(1);
            $table->integer('c_board_view_percent_type')->default(2);
            $table->string('c_task_stage', 128)->default('Etapa');
            $table->string('c_task_name', 128)->default('Tarefa');
            $table->string('c_task_status_name', 256)->default('Não iniciado;Em andamento;Atrasado;Concluído;Cancelado');
            $table->string('c_task_status_color', 256)->default("#FFC000;#00B0F0;#00B050;#FF0000;#777777");

            $table->timestamps();
            $table->softDeletes();
        });

        /**  Tabela de configuração de opções do sistema de ações
         *  ------------------------------------------------------------
         * id()                 => ID da Usuário;
         * @name                => Nome do Quadro;
         * @color               => Cor do Quadro;
         * @user_id             => Proprietário do Quadro;
         * @date_start          => Data de inicio do Quadro;
         * @date_end            => Data de fim do Quadro;
         * @auth_task_create    => Autorização para criação de tarefa | 0 = Somente o cirador pode criar, 1 = Todos os usuários podem criar;
         * @status              => Status do Quadro | 0 = Não iniciado; 1 = Em andamento, 2 = Atrasado, 3 = Concluído, 4 = Cancelado;
         * @percent             => Procentahgem concluida;
         *
         * @updated_by          => Informa o ID do ultimo usuário que atualizou o sistema;
         * timestamps()         => Cria os campos create_at (Criado em ) e update_at (atualizado em) na table;
         * softDeletes()        => Indica se o campo esta deletado "virtualmente"e a data que em o registro
         *                         foi deletado "virtualmente"
         */

        Schema::create('actions__board', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('description', 128);
            $table->string('color', 36)->default('#333');
            $table->bigInteger('user_id')->unsigned();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->integer('auth_task_create')->default(0);
            $table->integer('status')->default(0);
            $table->integer('percent')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('actions__board_member', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('board_id')->unsigned();
            $table->integer('type')->unsisgned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('actions__lists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('board_id')->unsisgned()->nullable();
            $table->string('title', 64);
            $table->integer('type')->unsisgned(); //0 Normal / 1 Tarefas não iniciadas / 2 Tarefas concluídas / 3 Tarefas canceladas
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('actions__tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 36);
            $table->string('description', 512)->nullable();
            $table->string('cover', 256)->nullable();
            $table->bigInteger('board_id')->unsisgned();
            $table->bigInteger('task_id')->unsisgned()->nullable();
            $table->integer('type')->unsisgned()->default(0); // 0 trefa | 0 subtarefa
            $table->bigInteger('list_origin_id')->unsigned();
            $table->bigInteger('list_actual_id')->unsigned();
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->integer('status')->default(0);
            $table->integer('nun_task')->unsigned()->nullable();
            $table->integer('order_task_list')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //* Criar etiquetas     */
        Schema::create('actions__label', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('board_id')->unsisgned();
            $table->string('name', 36);
            $table->string('color', 36)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        //* Criar comentarios   */
        Schema::create('actions__comment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('board_id')->unsisgned();
            $table->bigInteger('task_id')->unsisgned()->nullable();
            $table->bigInteger('user_id')->unsisgned();
            $table->string('comment', 512)->nullable();
            $table->integer('private')->nullable(); // 0 = não privado (publico) | 1 = privado
            $table->integer('type')->nullable(); // 0 = board | 1 = task
            $table->timestamps();
            $table->softDeletes();
        });

        //* Comementarios privados */
        Schema::create('actions__comment_private', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('comment_id')->unsisgned();
            $table->bigInteger('user_id')->unsisgned(); //usuario que vai ver o comentario
            $table->timestamps();
            $table->softDeletes();
        });

        //* Criar Anexos        */
        Schema::create('actions__attachment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('board_id')->unsisgned();
            $table->bigInteger('task_id')->unsisgned();
            $table->string('name', 128);
            $table->string('path', 256);
            $table->timestamps();
            $table->softDeletes();
        });

        //* Registro de Atividades - log       */
        Schema::create('actions__log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('board_id')->unsisgned();
            $table->bigInteger('task_id')->unsisgned();
            $table->bigInteger('user_id')->unsisgned();
            $table->string('description', 512)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        /* Notificações */
        Schema::create('actions__notification', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsisgned();
            $table->bigInteger('board_id')->unsisgned();
            $table->string('description', 512)->nullable();
            $table->integer('type')->nullable();
            $table->integer('status')->nullable(); // 0 = não lida | 1 = lida
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
        Schema::dropIfExists('actions__config');
        Schema::dropIfExists('actions__board');
        Schema::dropIfExists('actions__board_member');
        Schema::dropIfExists('actions__lists');
        Schema::dropIfExists('actions__tasks');
        Schema::dropIfExists('actions__label');
        Schema::dropIfExists('actions__comment');
        Schema::dropIfExists('actions__comment_private');
        Schema::dropIfExists('actions__attachment');
        Schema::dropIfExists('actions__log');
        Schema::dropIfExists('actions__notification');
    }
};

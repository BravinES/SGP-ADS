<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceitas extends Migration
{

    public function up()
    {

        Schema::create('receitas__iptu_totais_por_ano', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('year')->unsigned()->nullable();
            $table->double('launch_value', 15, 2);
            $table->integer('launch_unit');
            $table->double('collected_value', 15, 2);
            $table->integer('collected_unit');

            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('receitas__iptu_totais', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('year')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->double('launch_value', 15, 2)->nullable();
            $table->double('collected_value', 15, 2)->nullable();
            $table->integer('unit')->nullable();
            $table->string('type', 3)->nullable();
            $table->string('occupations', 3)->nullable();

            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down()
    {
        Schema::dropIfExists('receitas__iptu_totais_ano');
        Schema::dropIfExists('receitas__iptu_totais');
    }
}

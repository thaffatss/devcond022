<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createalltables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // Criando a tabela de usuários.
        Schema::create('users', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // Só pode ter um user com o msm email.
            $table->string('cpf')->unique();
            $table->string('password');
        });

        // Criando a tabela das unidades.
        Schema::create('unit', function(Blueprint $table){
            $table->id();
            $table->string('name'); //Ex: AP 801, L8Q27
            $table->integer('id_owner'); //Salva id do proprietário.
        });

        // Criando a tabela de pessoas.
        Schema::create('unitpeoples', function(Blueprint $table){
            $table->id();
            $table->integer('id_unit');
            $table->string('name');
            $table->date('birthdate');
        });

         // Criando a tabela dos veículos.
         Schema::create('unitvehicles', function(Blueprint $table){
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('color');
            $table->string('plate');
        });

        // Criando a tabela dos animais de estimação.
        Schema::create('unitpets', function(Blueprint $table){
            $table->id();
            $table->integer('id_unit');
            $table->string('name');
            $table->string('race');
        });

        // Criando a tabela para armazenar mural de avisos.
        Schema::create('walls', function(Blueprint $table){
            $table->id();
            $table->string('title');
            $table->string('body');
            $table->datetime('datecreated');
        });

        // Criando a tabela para armazenar os likes.
        Schema::create('walllikes', function(Blueprint $table){
            $table->id();
            $table->integer('id_wall'); // id do post do mural.
            $table->integer('id_user'); // id do usuário que deu like.
        });

        // Criando a tabela para armazenar os documentos.
        Schema::create('docs', function(Blueprint $table){
            $table->id();
            $table->integer('title'); 
            $table->integer('fileurl'); // url do arquivo.
        });

        // Criando a tabela para armazenar os boletos.
        Schema::create('billets', function(Blueprint $table){
            $table->id();
            $table->integer('id_unit');
            $table->integer('title'); 
            $table->integer('fileurl'); // url do arquivo.
        });

        // Criando a tabela para armazenar os livros de ocorrências(warnings).
        Schema::create('warnings', function(Blueprint $table){
            $table->id();
            $table->integer('id_unit');
            $table->integer('title'); 
            $table->string('status')->default('IN_REVIEW'); // IN_REVIEW, RESOLVED.
            $table->date('datecreated');
            $table->text('photos');
        });

        // Criando a tabela para armazenar os achados e perdidos.
        Schema::create('foundandlost', function(Blueprint $table){
            $table->id();
            $table->string('status')->default('LOST'); // LOST, RECOVERED.
            $table->string('photo');
            $table->string('description');
            $table->string('where');
            $table->date('datecreated');
        });

         // Criando a tabela para armazenar as areas.
         Schema::create('areas', function(Blueprint $table){
            $table->id();
            $table->integer('allowed')->default(1);
            $table->string('title');
            $table->string('cover');
            $table->string('days'); // 0,1,2,3,4,5,6.
            $table->time('start_time');
            $table->time('end_time');
        });
        
        // Criando a tabela para armazenar os dias indisponiveis das areas.
        Schema::create('areadisableddays', function(Blueprint $table){
            $table->id();
            $table->integer('id_area');
            $table->date('day');
        });

        // Criando a tabela para armazenar as reservas das areas.
        Schema::create('reservation', function(Blueprint $table){
            $table->id();
            $table->integer('id_unit');
            $table->integer('id_area');
            $table->datetime('reservation_date'); // dia da reserva.
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
        Schema::dropIfExists('unit');
        Schema::dropIfExists('unitpeoples');
        Schema::dropIfExists('unitvehicles');
        Schema::dropIfExists('unitpets');
        Schema::dropIfExists('walls');
        Schema::dropIfExists('walllikes');
        Schema::dropIfExists('docs');
        Schema::dropIfExists('billets');
        Schema::dropIfExists('warnings');
        Schema::dropIfExists('foundandlost');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('areadisableddays');
        Schema::dropIfExists('reservation');
    }
}

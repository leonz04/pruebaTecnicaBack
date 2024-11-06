<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Campo auto-incremental 'id'
            $table->string('first_name', 20);  // Campo 'first_name' con longitud máxima de 20 caracteres
            $table->string('middle_name', 50)->nullable();  // Campo 'middle_name' opcional
            $table->string('last_name', 20);  // Campo 'last_name' con longitud máxima de 20 caracteres
            $table->string('second_last_name', 20);  // Campo 'second_last_name' con longitud máxima de 20 caracteres
            $table->string('country');  // Campo 'country'
            $table->string('identification_type');  // Campo 'identification_type'
            $table->string('identification_number', 20)->unique();  // Campo 'identification_number' único
            $table->string('email')->unique();  // Campo 'email' único
            $table->date('hire_date');  // Campo 'hire_date' tipo fecha
            $table->string('area');  // Campo 'area'
            $table->string('status');  // Campo 'status'
            $table->timestamps();  // Campos 'created_at' y 'updated_at'
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

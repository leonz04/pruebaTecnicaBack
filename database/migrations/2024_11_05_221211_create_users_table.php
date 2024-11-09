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
            $table->enum('country', ['Colombia', 'Estados Unidos']);  // Restricción de país
            $table->enum('identification_type', ['Cédula de Ciudadanía', 'Cédula de Extranjería', 'Pasaporte', 'Permiso Especial']);  // Restricción de tipo de identificación
            $table->string('identification_number', 20);  // Campo 'identification_number' con longitud máxima de 20 caracteres
            $table->string('email', 300)->unique();  // Campo 'email' único con longitud máxima de 300 caracteres
            $table->date('hire_date');  // Campo 'hire_date' tipo fecha
            $table->string('area');  // Campo 'area'
            $table->string('status');  // Campo 'status'
            $table->timestamps();  // Campos 'created_at' y 'updated_at'

            // Clave única compuesta para 'identification_type' y 'identification_number'
            $table->unique(['identification_type', 'identification_number']);
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

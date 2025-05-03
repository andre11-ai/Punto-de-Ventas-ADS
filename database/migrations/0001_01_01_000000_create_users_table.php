<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Creamos la tabla users
        Schema::create('users', function (Blueprint $table) {
            $table->id();                                // AUTO_INCREMENT
            $table->string('name');                      // Nombre
            $table->string('email')->unique();           // Correo único
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');                  // Contraseña
            $table->enum('rol', ['Super-Admin','Admin','User'])
                  ->default('User');                     // Valor por defecto
            $table->rememberToken();
            $table->timestamps();
        });

        // Forzar el AUTO_INCREMENT inicial a 11101
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1110001;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

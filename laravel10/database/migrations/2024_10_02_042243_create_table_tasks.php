<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)
                ->comment('Titulo de la tarea, no nulo');

            $table->string('description')
                ->comment('descripcion de la tarea, no nulo');
            
            $table->date('expires')->nullable()
                ->comment('Fecha de vencimiento, puede ser nulo');
            
            $table->string('image', 100)->nullable()
                ->comment('Imagen de la tarea, puede ser nulo');

            $table->string('status', 15)->default('PENDIENTE')
                ->comment('Estado de la tarea PENDIENTE|HECHO');
            
            $table->string('tags', 250)->nullable()
                ->comment('Una cadena de etiquetas separado por comas');
            
            $table->foreignId('user_id')->constrained('users')
                ->onDelete('cascade');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

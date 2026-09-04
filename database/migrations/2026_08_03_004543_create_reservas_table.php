<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->foreignId('peluquero_id')->constrained('peluqueros');
            $table->foreignId('servicio_id')->constrained('servicios');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['Pendiente', 'Confirmada', 'Cancelada', 'Finalizada', 'Expirada'])->default('Pendiente');
            $table->string('codigo_qr', 255)->unique()->nullable();
            $table->dateTime('fecha_expiracion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('fecha');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->unique()->constrained('reservas');
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['QR', 'Efectivo'])->default('QR');
            $table->string('comprobante', 255)->nullable();
            $table->enum('estado', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->dateTime('fecha_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
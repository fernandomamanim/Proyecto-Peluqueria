<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
        });

        DB::statement('ALTER TABLE reservas MODIFY usuario_id BIGINT UNSIGNED NULL');

        Schema::table('reservas', function (Blueprint $table) {
            $table->string('nombre_invitado', 150)->nullable()->after('usuario_id');
            $table->string('telefono_invitado', 20)->nullable()->after('nombre_invitado');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropColumn(['nombre_invitado', 'telefono_invitado']);
        });

        DB::statement('ALTER TABLE reservas MODIFY usuario_id BIGINT UNSIGNED NOT NULL');

        Schema::table('reservas', function (Blueprint $table) {
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }
};
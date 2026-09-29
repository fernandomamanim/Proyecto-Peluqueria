<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peluqueros', function (Blueprint $table) {
            $table->decimal('salario_mensual', 10, 2)->default(0)->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('peluqueros', function (Blueprint $table) {
            $table->dropColumn('salario_mensual');
        });
    }
};
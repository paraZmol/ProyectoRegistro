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
        Schema::table('prestamos', function (Blueprint $table) {
            // opciones del "combo"
            $options = [
                'Lectura de libro digital',
                'Uso de BD para investigación',
                'Trabajo universitario',
                'Otro',
            ];

            //nueva columna, solo tablet
            $table->enum('actividad_tablet', $options)
                  ->nullable()
                  ->after('item_id');

            // columna para "otro" (nullable)
            $table->string('actividad_tablet_otro')->nullable()->after('actividad_tablet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestamos', function (Blueprint $table) {
            // Para poder deshacer la migración
            $table->dropColumn('actividad_tablet');
            $table->dropColumn('actividad_tablet_otro');
        });
    }
};

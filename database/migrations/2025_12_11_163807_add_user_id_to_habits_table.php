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
        Schema::table('habits', function (Blueprint $table) {
            // Agregamos la columna user_id, relacionada con la tabla users
            $table->foreignId('user_id')
                ->after('id') // 'after' es para ordenar visualmente la columna en la BD
                ->constrained()
                ->onDelete('cascade'); // Si se borra el usuario, se borran sus hábitos
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

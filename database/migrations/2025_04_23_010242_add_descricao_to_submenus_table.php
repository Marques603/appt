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
        Schema::table('submenus', function (Blueprint $table) {
            $table->string('description')->nullable(); // Adiciona a coluna 'descricao'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submenus', function (Blueprint $table) {
            $table->dropColumn('description'); // Remove a coluna 'descricao' se necessário
        });
    }
};

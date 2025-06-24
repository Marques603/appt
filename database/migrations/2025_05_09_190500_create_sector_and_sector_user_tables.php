<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabela principal de setores
        Schema::create('sector', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('acronym')->nullable();
            $table->boolean('status')->default(1); // 1 = ativo, 0 = inativo
            $table->timestamps();
            $table->softDeletes(); // Caso deseje restaurar depois
        });

        // Tabela pivô sector_user
        Schema::create('sector_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('sector')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela pivô sector_responsible_user
        Schema::create('sector_responsible_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('sector')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sector_responsible_user');
        Schema::dropIfExists('sector_user');
        Schema::dropIfExists('sector');
    }
};

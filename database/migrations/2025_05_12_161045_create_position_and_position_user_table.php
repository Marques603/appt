<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de cargo (position)
        Schema::create('position', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do cargo
            $table->string('description'); // Decrição do cargo
            $table->boolean('status')->default(1); // Status (ativo/inativo)
            $table->timestamps();
            $table->softDeletes(); // Soft delete para o cargo
        });

        // Tabela pivô entre 'position' e 'user'
        Schema::create('position_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('position')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_user');
        Schema::dropIfExists('position');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Código do documento
            $table->string('name'); // Usando 'name'
            $table->string('path');
            $table->string('extension', 10)->nullable(); // Usando 'extension'
            $table->string('revision')->nullable();
            $table->unsignedBigInteger('user_upload'); // ID do usuário que fez o upload
            $table->unsignedBigInteger('size')->nullable(); // Usando 'size'
            $table->text('description')->nullable(); // Usando 'description'
            $table->boolean('status')->default(1); // 1 para ativo, 0 para inativo
            $table->timestamps();
            $table->softDeletes(); // Adicionando softDeletes
        });
    }

    public function down(): void {
        Schema::dropIfExists('archives');
    }
};


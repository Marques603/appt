<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('folder_subfolder', function (Blueprint $table) {
            $table->id(); // Adicionando ID para consistência com suas pivos existentes
            $table->foreignId('folder_id')->constrained('folders')->onDelete('cascade');
            $table->foreignId('subfolder_id')->constrained('subfolders')->onDelete('cascade');
            // Não precisa de primary key composta se tiver um ID auto-increment
            $table->timestamps();
            $table->softDeletes(); // Adicionando softDeletes
        });
    }

    public function down(): void {
        Schema::dropIfExists('folder_subfolder');
    }
};
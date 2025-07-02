<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subfolder_sector', function (Blueprint $table) { // Nome da tabela pivo consistente
            $table->id(); // Adicionando ID
            $table->foreignId('subfolder_id')->constrained('subfolders')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sector')->onDelete('cascade'); // Referenciando 'sector' singular
            $table->timestamps();
            $table->softDeletes(); // Adicionando softDeletes
        });
    }

    public function down(): void {
        Schema::dropIfExists('subfolder_sector');
    }
};
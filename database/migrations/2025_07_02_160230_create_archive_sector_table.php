<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('archive_sector', function (Blueprint $table) { // Nome da tabela pivo consistente
            $table->id(); // Adicionando ID
            $table->foreignId('archive_id')->constrained('archives')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sector')->onDelete('cascade'); // Referenciando 'sector' singular
            $table->timestamps();
            $table->softDeletes(); // Adicionando softDeletes
        });
    }

    public function down(): void {
        Schema::dropIfExists('archive_sector');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabela principal de Cost Centers
        Schema::create('cost_center', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->nullable();
    $table->boolean('status')->default(1); // 1 = ativo, 0 = inativo
    $table->timestamps();
    $table->softDeletes();
});

Schema::create('cost_center_sector', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cost_center_id')->constrained('cost_center')->onDelete('cascade');
    $table->foreignId('sector_id')->constrained('sector')->onDelete('cascade');
    $table->timestamps();
    $table->softDeletes();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('cost_center_sector');
        Schema::dropIfExists('cost_center');
    }
};

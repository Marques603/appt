<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('notes', function (Blueprint $table) {
        $table->id();
        $table->string('provider');
        $table->foreignId('approval_position_id')->nullable()->constrained('position')->nullOnDelete();
        $table->foreignId('cost_center_id')->constrained('cost_center')->cascadeOnDelete();
        $table->string('description')->nullable();
        $table->decimal('valor', 12, 2);
        $table->string('note_number')->unique();
        $table->date('payday');
        $table->enum('status', [
            'Aguardando Aprovação',
            'Aprovada pelo Diretor',
            'Reprovada',
            'Lançada no Financeiro',
            'Paga'
        ])->default('Aguardando Aprovação');

        // Novo campo obrigatório para o arquivo PDF:
        $table->string('pdf_file');  // obrigatório, então NÃO usa nullable()

        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
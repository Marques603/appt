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
            $table->foreignId('cost_center_id')->constrained('cost_centers')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->decimal('valor', 12, 2);
            $table->string('note_number')->nullable(); // ou ->default('0000')
            $table->date('payday');
            $table->enum('status', [
                'Aguardando Aprovação',
                'Aprovada pelo Diretor',
                'Reprovada',
                'Lançada no Financeiro',
                'Enviada para Pagamento',
                'Paga'
            ])->default('Aguardando Aprovação');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
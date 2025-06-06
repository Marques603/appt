<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsWithRelatedTables extends Migration
{
    public function up()
    {
        // Tabela de documentos
        Schema::create('document', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // Código do documento
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_upload'); // ID do usuário que fez o upload
            $table->string('revision')->nullable();
            $table->string('file_path');
            $table->string('file_type');
            $table->boolean('status')->default(1); // 1 para ativo, 0 para inativo
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_upload')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabela pivô para documentos e macros
        Schema::create('document_macro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('macro_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('document_id')->references('id')->on('document')->onDelete('cascade');
            $table->foreign('macro_id')->references('id')->on('macro')->onDelete('cascade');
        });

        // Tabela pivô para documentos e setores (não cria a tabela 'sector' pois já existe)
        Schema::create('document_sector', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('sector_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('document_id')->references('id')->on('document')->onDelete('cascade');
            $table->foreign('sector_id')->references('id')->on('sector')->onDelete('cascade');
        });

        // Tabela de aprovações de documentos
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id'); // Referência para o documento
            $table->unsignedBigInteger('user_id'); // Referência para o usuário que aprovou
            $table->tinyInteger('status')->default(0); // Status da aprovação (0: pendente, 1: aprovado, 2: reprovado)
            $table->text('comments')->nullable(); // Comentários do aprovador
            $table->timestamp('approved_at'); // Data de aprovação
            $table->string('approval_type')->nullable(); // Tipo de aprovação (ex: "Aprovação", "Revisão", etc.)
            $table->string('approval_level')->nullable(); // Nível de aprovação (ex: "Gerente", "Diretor", etc.)          
            $table->timestamps();
            $table->softDeletes();

            // Chaves estrangeiras
            $table->foreign('document_id')->references('id')->on('document')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        // Tabela de histórico de arquivos de documentos

        Schema::create('document_file_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('document')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabela de logs de acesso aos documentos

        Schema::create('document_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('document')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

    }



    public function down()
    {
        // Remover as tabelas criadas
        Schema::dropIfExists('document_approvals');
        Schema::dropIfExists('document_sector');
        Schema::dropIfExists('document_macro');
        Schema::dropIfExists('document');
    }
}

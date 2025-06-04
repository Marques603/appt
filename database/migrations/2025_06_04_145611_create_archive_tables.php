<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchiveTables extends Migration
{
    public function up()
    {
        // Tabela principal: archive
        Schema::create('archive', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_upload');
            $table->string('revision')->nullable();
            $table->string('file_path');
            $table->string('file_type');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_upload')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabela pivô: archive_macro
        Schema::create('archive_macro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archive_id');
            $table->unsignedBigInteger('macro_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('archive_id')->references('id')->on('archive')->onDelete('cascade');
            $table->foreign('macro_id')->references('id')->on('macro')->onDelete('cascade');
        });

        // Tabela pivô: archive_sector
        Schema::create('archive_sector', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archive_id');
            $table->unsignedBigInteger('sector_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('archive_id')->references('id')->on('archive')->onDelete('cascade');
            $table->foreign('sector_id')->references('id')->on('sector')->onDelete('cascade');
        });

        // Tabela de aprovações
        Schema::create('archive_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archive_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('status')->default(0);
            $table->text('comments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approval_type')->nullable();
            $table->string('approval_level')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('archive_id')->references('id')->on('archive')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Histórico de arquivos
        Schema::create('archive_file_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_id')->constrained('archive')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('archive_file_history');
        Schema::dropIfExists('archive_approvals');
        Schema::dropIfExists('archive_sector');
        Schema::dropIfExists('archive_macro');
        Schema::dropIfExists('archive');
    }
}

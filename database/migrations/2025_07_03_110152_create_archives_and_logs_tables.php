<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_upload'); // usuário que fez upload
            $table->string('revision')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->boolean('status')->default(1); // 1 ativo, 0 inativo
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_upload')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('archive_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_id')->constrained('archives')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
        Schema::create('archive_sector', function (Blueprint $table) {
    $table->id();
    $table->foreignId('archive_id')->constrained('archives')->onDelete('cascade');
    $table->foreignId('sector_id')->constrained('sector')->onDelete('cascade');
    $table->timestamps();
    $table->softDeletes();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('archive_logs');
        Schema::dropIfExists('archives');
    }
};

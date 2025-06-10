<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
      public function up(): void
    {
        // Tabela categorie
        Schema::create('categorie', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true); // 1 = ativa, 0 = inativa
            $table->timestamps();
            $table->softDeletes(); // <- soft deletes
        });

        // Tabela pivô categorie_responsible_user
        Schema::create('categorie_responsible_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categorie')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorie_responsible_user');
        Schema::dropIfExists('categorie');
    }
};

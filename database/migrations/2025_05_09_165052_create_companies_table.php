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
        Schema::create('company', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('corporate_name'); // Razão social
        $table->string('cnpj', 18)->unique();
        $table->boolean('status')->default(1); // 1 = ativa, 0 = inativa
        $table->timestamps();
        $table->softDeletes(); 


    });

    Schema::create('company_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->constrained('company')->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes(); 
    });

    // Tabela pivô sector_responsible_user
    Schema::create('company_responsible_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->constrained('company')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

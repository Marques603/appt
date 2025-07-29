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
        Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('plate')->unique();
        $table->string('brand');
        $table->string('model');
        $table->integer('current_km');
        $table->tinyInteger('status')->default(1); // 1: disponível, 2: em trânsito
        $table->text('observations')->nullable();
        $table->timestamps();
        $table->softDeletes();
});
        Schema::create('vehicle_movements', function (Blueprint $table) {
        $table->id();

        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quem levou
        $table->foreignId('gatekeeper_id')->constrained('users')->onDelete('cascade'); // Quem registrou

        $table->integer('departure_km');
        $table->integer('return_km')->nullable();

        $table->timestamp('departure_time');
        $table->timestamp('return_time')->nullable();

        $table->string('destination');
        $table->text('observations')->nullable();

        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

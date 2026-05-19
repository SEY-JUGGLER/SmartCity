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
    Schema::create('signalements', function (Blueprint $table) {
    $table->id();
    $table->string('position');
    $table->string('categories');
    $table->string('description');
    $table->string('statut')->default('enAttente'); // enum Statut
    $table->enum('priorite', ['critique', 'moyenne', 'faible'])->default('faible');
    $table->date('dateSignalement')->useCurrent();
    $table->string('photodoc')->nullable();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // citoyen
    $table->foreignId('zone_id')->nullable()->constrained()->nullOnDelete();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};

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
    Schema::create('rapports', function (Blueprint $table) {
    $table->id();
    $table->dateTime('dateGeneration')->useCurrent();
    $table->integer('nbrSignalement')->default(0);
    $table->double('quantiteOrdure')->nullable();
    $table->integer('tempsMoyenneTraitement')->nullable(); // en minutes
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // admin
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};

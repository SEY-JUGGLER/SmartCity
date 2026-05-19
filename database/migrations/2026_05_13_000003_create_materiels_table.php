<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('categorie')->default('equipement');
            $table->string('statut')->default('disponible')->comment('disponible, attribue, en_maintenance, hors_service');
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('date_attribution')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};

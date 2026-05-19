<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signalement_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('signalement_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('type')->default('citoyen')->comment('citoyen, avant, apres');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signalement_photos');
    }
};

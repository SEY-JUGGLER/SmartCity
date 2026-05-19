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
        Schema::table('signalements', function (Blueprint $table) {
            if (! Schema::hasColumn('signalements', 'priorite')) {
                $table->enum('priorite', ['critique', 'moyenne', 'faible'])->default('faible');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signalements', function (Blueprint $table) {
            $table->dropColumn('priorite');
        });
    }
};

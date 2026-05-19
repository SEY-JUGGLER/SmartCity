<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('signalements', function (Blueprint $table) {
            $table->timestamp('date_resolution')->nullable()->after('dateSignalement');
            $table->text('commentaire_agent')->nullable()->after('description');
            $table->text('commentaire_admin')->nullable()->after('commentaire_agent');
        });
    }

    public function down(): void
    {
        Schema::table('signalements', function (Blueprint $table) {
            $table->dropColumn(['date_resolution', 'commentaire_agent', 'commentaire_admin']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'age')) {
                $table->unsignedSmallInteger('age')->nullable()->after('prenom');
            }
            if (! Schema::hasColumn('users', 'localite')) {
                $table->string('localite')->nullable()->after('age');
            }
            if (! Schema::hasColumn('users', 'photoProfi')) {
                $table->string('photoProfi')->nullable()->after('localite');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists('age');
            $table->dropColumnIfExists('localite');
            $table->dropColumnIfExists('photoProfi');
        });
    }
};

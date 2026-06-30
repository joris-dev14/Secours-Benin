<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('ambulances', 'statut')) {
            Schema::table('ambulances', function (Blueprint $table) {
                $table->string('statut')->default('disponible')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ambulances', 'statut')) {
            Schema::table('ambulances', function (Blueprint $table) {
                $table->string('statut')->default('disponible')->change();
            });
        }
    }
};

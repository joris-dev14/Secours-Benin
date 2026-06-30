<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('communes', 'centre_samu')) {
            Schema::table('communes', function (Blueprint $table) {
                $table->string('centre_samu')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('communes', 'centre_samu')) {
            Schema::table('communes', function (Blueprint $table) {
                $table->string('centre_samu')->nullable(false)->change();
            });
        }
    }
};
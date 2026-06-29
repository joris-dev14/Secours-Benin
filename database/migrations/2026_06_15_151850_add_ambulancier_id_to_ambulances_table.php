<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ambulances', function (Blueprint $table) {
            $table->foreignId('ambulancier_id')->nullable()->constrained('ambulanciers')->nullOnDelete();
            $table->string('modele')->nullable()->after('matricule');
        });
    }

    public function down()
    {
        Schema::table('ambulances', function (Blueprint $table) {
            $table->dropForeign(['ambulancier_id']);
            $table->dropColumn(['ambulancier_id', 'modele']);
        });
    }
};
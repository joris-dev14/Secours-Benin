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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alerte_id')->constrained('alertes');
            $table->foreignId('ambulance_id')->constrained('ambulances');
            $table->timestamp('depart_a')->nullable();
            $table->timestamp('arrive_a')->nullable();
            $table->timestamp('termine_a')->nullable();
            $table->enum('statut', ['assignee', 'en_route', 'sur_place', 'terminee'])->default('assignee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};

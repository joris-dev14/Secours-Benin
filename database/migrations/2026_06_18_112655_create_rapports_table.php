<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins');
            $table->string('titre');
            $table->enum('type', ['global', 'flotte', 'chauffeurs', 'securite']);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('centre')->nullable();
            $table->string('commune')->nullable();
            $table->enum('format', ['pdf', 'excel', 'csv']);
            $table->string('fichier');
            $table->unsignedBigInteger('taille');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
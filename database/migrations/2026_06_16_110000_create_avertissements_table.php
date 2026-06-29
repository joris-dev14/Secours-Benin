<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avertissements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citoyen_id')->constrained('citoyens');
            $table->foreignId('admin_id')->constrained('admins');
            $table->text('message');
            $table->enum('statut', ['envoye', 'lu'])->default('envoye');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avertissements');
    }
};
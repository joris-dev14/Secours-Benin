<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('alertes')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::create('alertes_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citoyen_id')->constrained('citoyens');
            $table->string('commune');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->string('statut')->default('en_attente');
            $table->timestamps();
        });

        DB::statement('INSERT INTO alertes_new (id, citoyen_id, commune, latitude, longitude, photo, description, statut, created_at, updated_at) SELECT id, citoyen_id, commune, latitude, longitude, photo, description, statut, created_at, updated_at FROM alertes');

        Schema::drop('alertes');
        Schema::rename('alertes_new', 'alertes');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        if (!Schema::hasTable('alertes')) {
            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::create('alertes_old', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citoyen_id')->constrained('citoyens');
            $table->string('commune');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->enum('statut', ['en_attente', 'prise_en_charge', 'terminee', 'annulee'])->default('en_attente');
            $table->timestamps();
        });

        DB::statement('INSERT INTO alertes_old (id, citoyen_id, commune, latitude, longitude, photo, description, statut, created_at, updated_at) SELECT id, citoyen_id, commune, latitude, longitude, photo, description, statut, created_at, updated_at FROM alertes');

        Schema::drop('alertes');
        Schema::rename('alertes_old', 'alertes');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};

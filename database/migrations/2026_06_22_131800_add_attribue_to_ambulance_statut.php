<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'attribue' to the enum values of ambulances.statut
        DB::statement("ALTER TABLE `ambulances` MODIFY `statut` ENUM('disponible','en_mission','maintenance','attribue') NOT NULL DEFAULT 'disponible'");
    }

    public function down(): void
    {
        // Revert: remove 'attribue' from the enum values
        DB::statement("ALTER TABLE `ambulances` MODIFY `statut` ENUM('disponible','en_mission','maintenance') NOT NULL DEFAULT 'disponible'");
    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nom'          => 'Directeur',
            'prenom'       => 'SAMU',
            'matricule'    => 'ADMIN-001',
            'mot_de_passe' => Hash::make('admin123'),
            'role'         => 'super_admin',
            'statut'       => 'actif',
        ]);
    }
}
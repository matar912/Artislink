<?php

namespace Database\Seeders;

use App\Models\Artisan;
use App\Models\Produit;
use App\Models\User;
use App\Models\Visiteur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un Admin par défaut
        User::factory()->admin()->create([
            'nom' => 'Artislink',
            'prenom' => 'Admin',
            'email' => 'admin@artislink.com',
            'password' => Hash::make('password123'),
        ]);

        // Créer quelques artisans avec des produits
        Artisan::factory(5)->create()->each(function ($artisan) {
            Produit::factory(3)->create([
                'artisan_id' => $artisan->id,
            ]);
        });

        // Créer quelques visiteurs
        Visiteur::factory(10)->create();
    }
}

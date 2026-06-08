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
    public function run(): void
    {
        // 1. Création de l'Administrateur
        User::create([
            'prenom' => 'Mamadou',
            'nom' => 'Diop',
            'email' => 'admin@artislink.sn',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'est_actif' => true,
            'telephone' => '771234567',
        ]);

        // 2. Création de 3 Artisans
        $artisans_data = [
            [
                'prenom' => 'Aminata',
                'nom' => 'Diallo',
                'email' => 'aminata.diallo@artisan.sn',
                'ville' => 'Dakar',
                'cat' => 'Poterie',
                'desc' => 'Spécialiste de la poterie traditionnelle en terre cuite depuis 15 ans.',
                'produits' => [
                    ['nom' => 'Vase Terracotta Royal', 'prix' => 15000, 'img' => 'https://images.unsplash.com/photo-1595111051515-56885368a5c3?w=800'],
                    ['nom' => 'Bol Émaillé Bleu', 'prix' => 5500, 'img' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?w=800'],
                ]
            ],
            [
                'prenom' => 'Moussa',
                'nom' => 'Sarr',
                'email' => 'moussa.sarr@artisan.sn',
                'ville' => 'Saint-Louis',
                'cat' => 'Couture',
                'desc' => 'Maître tailleur spécialisé dans le Wax et le Bogolan authentique.',
                'produits' => [
                    ['nom' => 'Tunique Bogolan Homme', 'prix' => 25000, 'img' => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=800'],
                    ['nom' => 'Robe Wax Moderne', 'prix' => 35000, 'img' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800'],
                ]
            ],
            [
                'prenom' => 'Fatou',
                'nom' => 'Ndiaye',
                'email' => 'fatou.ndiaye@artisan.sn',
                'ville' => 'Saly',
                'cat' => 'Bijouterie',
                'desc' => 'Créatrice de bijoux uniques utilisant des perles et des métaux locaux.',
                'produits' => [
                    ['nom' => 'Collier Perles Baoulé', 'prix' => 12000, 'img' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800'],
                    ['nom' => 'Bracelet Cuivre Sculpté', 'prix' => 8500, 'img' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=800'],
                ]
            ],
        ];

        foreach ($artisans_data as $data) {
            $user = User::create([
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'artisan',
                'est_actif' => true,
                'telephone' => '77' . rand(1000000, 9999999),
            ]);

            $artisan = Artisan::create([
                'user_id' => $user->id,
                'categorie' => $data['cat'],
                'description' => $data['desc'],
                'ville' => $data['ville'],
            ]);

            foreach ($data['produits'] as $p) {
                Produit::create([
                    'artisan_id' => $artisan->id,
                    'nom' => $p['nom'],
                    'description' => "Une création unique faite à la main par " . $data['prenom'] . " " . $data['nom'],
                    'prix' => $p['prix'],
                    'stock' => rand(5, 20),
                    'categorie_produit' => $data['cat'],
                    'image_principale' => $p['img'],
                    'est_disponible' => true,
                ]);
            }
        }

        // 3. Création de 2 Visiteurs
        $visiteurs_data = [
            ['prenom' => 'Jean', 'nom' => 'Dupont', 'email' => 'jean.dupont@visiteur.sn'],
            ['prenom' => 'Awa', 'nom' => 'Faye', 'email' => 'awa.faye@visiteur.sn'],
        ];

        foreach ($visiteurs_data as $data) {
            $user = User::create([
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'visiteur',
                'est_actif' => true,
                'telephone' => '70' . rand(1000000, 9999999),
            ]);

            Visiteur::create([
                'user_id' => $user->id,
                'ville' => 'Dakar',
                'pays' => 'Sénégal',
            ]);
        }
    }
}

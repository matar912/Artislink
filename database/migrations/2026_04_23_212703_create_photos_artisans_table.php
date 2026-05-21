<?php

// ============================================================
// FICHIER : database/migrations/xxxx_creer_photos_artisan.php
//
// RÔLE DE CETTE TABLE :
// Stocke les photos de la galerie de chaque artisan.
// Un artisan peut avoir plusieurs photos.
// Une photo peut être désignée comme "photo de couverture".
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos_artisan', function (Blueprint $table) {

            $table->id();

            // L'artisan propriétaire de cette photo
            $table->foreignId('artisan_id')
                  ->constrained()
                  ->cascadeOnDelete(); // supprime les photos si l'artisan est supprimé

            // Chemin du fichier dans le stockage Laravel
            // Exemple : "artisans/3/photos/atelier.jpg"
            // On utilise asset('storage/' . $url) pour afficher l'image
            $table->string('url');

            // Description courte de la photo (optionnelle)
            $table->string('legende', 200)->nullable();

            // Est-ce la photo principale du profil ?
            // Un seul true par artisan (on gère ça dans le contrôleur)
            $table->boolean('est_couverture')->default(false);

            // Position dans la galerie (0 = premier, 1 = deuxième, etc.)
            $table->unsignedTinyInteger('ordre')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos_artisan');
    }
};

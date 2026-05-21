<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Table des avis ────────────────────────────────────────────────────
        Schema::create('avis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visiteur_id')
                  ->constrained('visiteurs')
                  ->cascadeOnDelete();

            $table->foreignId('artisan_id')
                  ->constrained('artisans')
                  ->cascadeOnDelete();

            // Lié à une commande pour prouver l'achat (facultatif)
            $table->foreignId('commande_id')
                  ->nullable()
                  ->constrained('commandes')
                  ->nullOnDelete();

            $table->tinyInteger('note'); 
            $table->text('commentaire')->nullable();
            $table->boolean('est_publie')->default(true);

            $table->timestamps();

            // Un visiteur ne peut noter un artisan qu'une fois (pour son profil global)
            // Ou on pourrait autoriser un avis par produit/commande plus tard.
            $table->unique(['visiteur_id', 'artisan_id']);
        });

        // ── Table des messages ────────────────────────────────────────────────
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expediteur_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('destinataire_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->text('contenu');
            $table->boolean('est_lu')->default(false);
            $table->timestamp('lu_le')->nullable();

            $table->timestamps();
        });

        // ── Table des favoris ─────────────────────────────────────────────────
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visiteur_id')
                  ->constrained('visiteurs')
                  ->cascadeOnDelete();

            $table->foreignId('artisan_id')
                  ->constrained('artisans')
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['visiteur_id', 'artisan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favoris');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('avis');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Table des Produits ────────────────────────────────────────────────
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            
            // L'artisan qui vend le produit
            $table->foreignId('artisan_id')
                  ->constrained('artisans')
                  ->cascadeOnDelete();

            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            
            // Quantité en stock (null = illimité ou sur commande)
            $table->integer('stock')->nullable();
            
            // Catégorie de produit (ex: "Décoration", "Vêtement", "Ustensile")
            $table->string('categorie_produit')->nullable();

            // Photo principale du produit
            $table->string('image_principale')->nullable();

            $table->boolean('est_disponible')->default(true);
            $table->timestamps();
        });

        // ── Table des Commandes ───────────────────────────────────────────────
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            
            // Le visiteur qui commande
            $table->foreignId('visiteur_id')
                  ->constrained('visiteurs')
                  ->cascadeOnDelete();

            $table->decimal('montant_total', 10, 2);
            
            // États : en_attente, payee, expediee, livree, annulee
            $table->enum('statut', ['en_attente', 'confirmee', 'expediee', 'livree', 'annulee'])
                  ->default('en_attente');

            // Informations de livraison spécifiques à cette commande
            $table->string('adresse_livraison')->nullable();
            $table->string('ville_livraison')->nullable();
            $table->string('telephone_contact')->nullable();

            $table->text('notes_client')->nullable();

            $table->timestamps();
        });

        // ── Table Pivot Commande-Produit (Lignes de commande) ────────────────
        Schema::create('commande_produit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produit_id')->constrained()->cascadeOnDelete();
            
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2); // Prix au moment de l'achat
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_produit');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('produits');
    }
};

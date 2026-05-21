<?php

// ============================================================
// FICHIER : database/migrations/xxxx_creer_visiteurs.php
//
// RÔLE DE CETTE TABLE :
// Stocke les informations complémentaires des visiteurs.
// Séparée de "users" pour ne pas surcharger la table d'auth.
// Relation : un visiteur → un user (via user_id)
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visiteurs', function (Blueprint $table) {

            $table->id();

            // Lien vers le user correspondant
            $table->foreignId('user_id')
                  ->unique()           // un user = un seul profil visiteur
                  ->constrained()
                  ->cascadeOnDelete();

            // D'où vient le visiteur ?
            $table->string('pays', 100)->nullable();
            $table->string('ville', 100)->nullable();

            // Catégories préférées stockées en JSON
            // Exemple : ["potier", "tisserand"]
            // Laravel convertit automatiquement ce JSON en tableau PHP
            $table->json('preferences')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visiteurs');
    }
};

<?php

// ============================================================
// FICHIER : database/migrations/xxxx_creer_artisans.php
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artisans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                  ->unique()
                  ->constrained()
                  ->cascadeOnDelete();

            // Métier de l'artisan (ex: "potier", "tisserand")
            $table->string('categorie', 100);

            // Description de l'atelier
            $table->text('description')->nullable();

            // Localisation simplifiée
            $table->string('adresse')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('pays', 100)->default('Sénégal');

            // Note moyenne et nombre d'avis
            $table->decimal('note_moyenne', 3, 2)->default(0.00);
            $table->unsignedInteger('nombre_avis')->default(0);

            $table->boolean('est_verifie')->default(false);
            $table->boolean('est_actif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artisans');
    }
};

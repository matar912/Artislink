<?php

namespace App\Models;
// ============================================================
// FICHIER : app/Models/Message.php
//
// NOTE : Dans un vrai projet, chaque classe serait dans son
// propre fichier. Ici on les regroupe pour aller plus vite.
// Créez app/Models/Message.php et copiez uniquement cette classe.
// ============================================================
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{

    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'contenu',
        'est_lu',
        'lu_le',
    ];

    protected $casts = [
        'est_lu' => 'boolean',
        'lu_le'  => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    /**
     * L'expéditeur du message.
     * On précise 'expediteur_id' car ce n'est pas la clé par défaut 'user_id'.
     * Utilisation : $message->expediteur->name
     */
    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    /**
     * Le destinataire du message.
     * Utilisation : $message->destinataire->name
     */
    public function destinataire(): BelongsTo
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }

    // ── Méthode utilitaire ────────────────────────────────────────────────────

    /**
     * Marquer le message comme lu.
     * Utilisation : $message->marquerCommeLu()
     */
    public function marquerCommeLu(): void
    {
        // On ne fait rien si déjà lu (évite une requête inutile)
        if (! $this->est_lu) {
            $this->update([
                'est_lu' => true,
                'lu_le'  => now(),
            ]);
        }
    }
}


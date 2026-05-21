<?php

namespace App\Models;

// ============================================================
// FICHIER : app/Models/Avis.php
// ============================================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    use HasFactory;

    protected $table = 'avis';

    protected $fillable = [
        'visiteur_id',
        'artisan_id',
        'commande_id',
        'note',
        'commentaire',
        'est_publie',
    ];

    protected $casts = [
        'est_publie' => 'boolean',
    ];

    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(Visiteur::class, 'visiteur_id');
    }

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    /**
     * L'avis est lié à une commande.
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Avis $avis) {
            $avis->artisan->mettreAJourNote();
        });

        static::deleted(function (Avis $avis) {
            $avis->artisan->mettreAJourNote();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ============================================================
// FICHIER : app/Models/Favori.php
// ============================================================

class Favori extends Model
{
    use HasFactory;

    protected $table = 'favoris';

    protected $fillable = [
        'visiteur_id',
        'artisan_id',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    /** Utilisation : $favori->visiteur->user->name */
    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(Visiteur::class, 'visiteur_id');
    }

    /** Utilisation : $favori->artisan->user->name */
    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }
}


<?php

namespace App\Models;

// ============================================================
// FICHIER : app/Models/Visiteur.php
// ============================================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visiteur extends Model
{
    use HasFactory;

    protected $table = 'visiteurs';

    protected $fillable = [
        'user_id',
        'pays',
        'ville',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Toutes les commandes du visiteur.
     */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'visiteur_id');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class, 'visiteur_id');
    }

    public function favoris(): HasMany
    {
        return $this->hasMany(Favori::class, 'visiteur_id');
    }
}

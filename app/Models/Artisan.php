<?php

namespace App\Models;

// ============================================================
// FICHIER : app/Models/Artisan.php
// ============================================================

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Artisan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'categorie',
        'description',
        'adresse',
        'ville',
        'region',
        'pays',
        'note_moyenne',
        'nombre_avis',
        'est_verifie',
        'est_actif',
    ];

    protected $casts = [
        'note_moyenne' => 'decimal:2',
        'est_verifie'  => 'boolean',
        'est_actif'    => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(PhotoArtisan::class)->orderBy('ordre');
    }

    public function photoCouverture(): HasOne
    {
        return $this->hasOne(PhotoArtisan::class)->where('est_couverture', true);
    }

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }

    public function produitsDisponibles(): HasMany
    {
        return $this->hasMany(Produit::class)->where('est_disponible', true);
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class)->where('est_publie', true);
    }

    public function favoris(): HasMany
    {
        return $this->hasMany(Favori::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeParCategorie(Builder $requete, string $categorie): Builder
    {
        return $requete->where('categorie', $categorie);
    }

    public function scopeParVille(Builder $requete, string $ville): Builder
    {
        return $requete->where('ville', 'ilike', "%{$ville}%");
    }

    public function scopeNoteMinimale(Builder $requete, float $noteMin): Builder
    {
        return $requete->where('note_moyenne', '>=', $noteMin);
    }

    public function scopeRechercher(Builder $requete, string $terme): Builder
    {
        return $requete->where(function ($sousRequete) use ($terme) {
            $sousRequete
                ->where('description', 'ilike', "%{$terme}%")
                ->orWhere('categorie', 'ilike', "%{$terme}%")
                ->orWhere('ville', 'ilike', "%{$terme}%")
                ->orWhereHas('user', function ($u) use ($terme) {
                    $u->where('name', 'ilike', "%{$terme}%");
                });
        });
    }

    public function mettreAJourNote(): void
    {
        $this->update([
            'note_moyenne' => round((float) $this->avis()->avg('note'), 2),
            'nombre_avis'  => $this->avis()->count(),
        ]);
    }
}

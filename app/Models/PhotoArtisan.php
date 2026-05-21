<?php

namespace App\Models;

// ============================================================
// FICHIER : app/Models/PhotoArtisan.php
// ============================================================

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoArtisan extends Model
{
    use HasFactory;

    protected $table = 'photos_artisan';

    protected $fillable = [
        'artisan_id',
        'url',            // chemin relatif dans le storage
        'legende',
        'est_couverture',
        'ordre',
    ];

    protected $casts = [
        'est_couverture' => 'boolean',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    // ── Accesseurs ────────────────────────────────────────────────────────────

    /**
     * Retourne l'URL complète de la photo.
     * Utilisation dans Vue : artisan.photo_couverture.url_complete
     */
    public function getUrlCompleteAttribute(): string
    {
        return asset('storage/' . $this->url);
    }
}

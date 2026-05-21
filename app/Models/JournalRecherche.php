<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// ============================================================
// FICHIER : app/Models/JournalRecherche.php
// ============================================================

class JournalRecherche extends Model
{
    use HasFactory;

    protected $table = 'journal_recherches';

    protected $fillable = [
        'user_id',         // null si l'utilisateur n'est pas connecté
        'terme',           // texte tapé dans la recherche
        'filtres',         // filtres appliqués (JSON)
        'nombre_resultats',
        'ip_anonymisee',   // IP avec le dernier octet masqué (RGPD)
    ];

    protected $casts = [
        'filtres' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

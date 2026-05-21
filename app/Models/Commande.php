<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'visiteur_id',
        'montant_total',
        'statut',
        'adresse_livraison',
        'ville_livraison',
        'telephone_contact',
        'notes_client',
    ];

    /**
     * Le visiteur qui a passé la commande.
     */
    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(Visiteur::class);
    }

    /**
     * Les produits inclus dans la commande.
     */
    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }

    /**
     * Les avis liés à cette commande.
     */
    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
    }
}

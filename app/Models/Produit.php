<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'artisan_id',
        'nom',
        'description',
        'prix',
        'stock',
        'categorie_produit',
        'image_principale',
        'est_disponible',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'stock' => 'integer',
        'est_disponible' => 'boolean',
    ];

    /**
     * L'artisan qui vend ce produit.
     */
    public function artisan(): BelongsTo
    {
        return $this->belongsTo(Artisan::class);
    }

    /**
     * Les commandes contenant ce produit.
     */
    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }
}

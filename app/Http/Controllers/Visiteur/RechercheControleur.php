<?php

namespace App\Http\Controllers\Visiteur;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Produit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RechercheControleur extends Controller
{
    /**
     * Affiche la boutique avec recherche et filtrage.
     */
    public function boutique(Request $request)
    {
        $query = Produit::query()->with(['artisan.user'])->where('est_disponible', true);

        // Recherche par mot-clé (nom ou description)
        if ($request->filled('recherche')) {
            $search = $request->recherche;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie_produit', $request->categorie);
        }

        $produits = $query->latest()->paginate(12)->withQueryString();

        $favorisIds = [];
        if (auth()->check() && auth()->user()->isVisiteur() && auth()->user()->visiteur) {
            $favorisIds = auth()->user()->visiteur->favoris()->pluck('artisan_id')->toArray();
        }

        return Inertia::render('Visitor/Boutique', [
            'produits' => $produits,
            'filtres' => $request->only(['recherche', 'categorie']),
            'favorisIds' => $favorisIds
        ]);
    }

    public function listerArtisans()
    {
        $artisans = Artisan::with('user')->get();
        return Inertia::render('Artisans/Liste', [
            'artisans' => $artisans
        ]);
    }

    public function voirArtisan(Artisan $artisan)
    {
        $artisan->load('user', 'produits', 'photos');
        return Inertia::render('Artisans/Voir', [
            'artisan' => $artisan
        ]);
    }
}

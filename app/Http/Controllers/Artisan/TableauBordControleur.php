<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;

class TableauBordControleur extends Controller
{
    /**
     * Afficher le tableau de bord de l'artisan.
     */
    public function afficher()
    {
        $user = Auth::user();
        $artisan = $user->artisan()->with('user')->first();
        
        // Sécurité : Si le profil artisan est manquant, on le crée
        if (!$artisan) {
            $artisan = \App\Models\Artisan::create([
                'user_id' => $user->id,
                'categorie' => 'À définir',
            ]);
            $artisan->load('user');
        }
        
        // On récupère les commandes qui contiennent au moins un produit de cet artisan
        $commandes = Commande::whereHas('produits', function($q) use ($artisan) {
            $q->where('artisan_id', $artisan->id);
        })->with(['produits' => function($q) use ($artisan) {
            $q->where('artisan_id', $artisan->id);
        }, 'visiteur.user'])->latest()->take(10)->get();

        return Inertia::render('Artisan/Dashboard', [
            'artisan' => $artisan,
            'commandes' => $commandes
        ]);
    }

    /**
     * Liste toutes les commandes reçues par l'artisan.
     */
    public function listerCommandes()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            $artisan = \App\Models\Artisan::create([
                'user_id' => $user->id,
                'categorie' => 'À définir',
            ]);
        }

        $commandes = Commande::whereHas('produits', function($q) use ($artisan) {
            $q->where('artisan_id', $artisan->id);
        })->with(['produits' => function($q) use ($artisan) {
            $q->where('artisan_id', $artisan->id);
        }, 'visiteur.user'])->latest()->get();

        return Inertia::render('Artisan/Commandes/Liste', [
            'commandes' => $commandes
        ]);
    }

    /**
     * Mettre à jour le statut d'une commande (confirmer, expédier, etc.).
     */
    public function changerStatut(Request $request, Commande $commande)
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        if (!$artisan) {
            abort(403, "Profil artisan non trouvé.");
        }

        // Vérifier que la commande contient bien des produits de cet artisan
        $appartientALartisan = $commande->produits()->where('artisan_id', $artisan->id)->exists();

        if (!$appartientALartisan) {
            abort(403);
        }

        $request->validate([
            'statut' => 'required|in:confirmee,expediee,livree,annulee',
        ]);

        $commande->update(['statut' => $request->statut]);

        event(new \App\Events\CommandeStatutMisAJour($commande));

        return back()->with('succes', 'Statut de la commande mis à jour.');
    }
}

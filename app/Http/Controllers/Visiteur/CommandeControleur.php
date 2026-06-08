<?php

namespace App\Http\Controllers\Visiteur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CommandeControleur extends Controller
{
    /**
     * Liste des commandes du visiteur.
     */
    public function lister()
    {
        $visiteur = Auth::user()->visiteur;
        $commandes = $visiteur->commandes()->with('produits.artisan.user')->latest()->get();
        
        return Inertia::render('Visitor/Dashboard', [
            'commandes' => $commandes
        ]);
    }

    /**
     * Passer une commande.
     */
    public function creer(Request $request)
    {
        $request->validate([
            'produits' => 'required|array',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'adresse_livraison' => 'required|string',
            'ville_livraison' => 'required|string',
            'telephone_contact' => 'required|string',
        ]);

        $visiteur = Auth::user()->visiteur;

        try {
            DB::beginTransaction();

            $commande = Commande::create([
                'visiteur_id' => $visiteur->id,
                'montant_total' => 0,
                'adresse_livraison' => $request->adresse_livraison,
                'ville_livraison' => $request->ville_livraison,
                'telephone_contact' => $request->telephone_contact,
                'notes_client' => $request->notes_client,
            ]);

            $total = 0;

            foreach ($request->produits as $item) {
                $produit = Produit::findOrFail($item['id']);
                $quantite = $item['quantite'];
                $prixUnitaire = $produit->prix;

                $commande->produits()->attach($produit->id, [
                    'quantite' => $quantite,
                    'prix_unitaire' => $prixUnitaire,
                ]);

                $total += $prixUnitaire * $quantite;

                // Mise à jour du stock si nécessaire
                if ($produit->stock !== null) {
                    $produit->decrement('stock', $quantite);
                }
            }

            $commande->update(['montant_total' => $total]);

            // Notifier les artisans concernés
            $artisanIds = $commande->produits()->pluck('artisan_id')->unique();
            foreach ($artisanIds as $artisanId) {
                event(new \App\Events\NouvelleCommandeRecue($commande, $artisanId));
            }

            DB::commit();

            return redirect()->route('visiteur.commandes.liste')
                             ->with('succes', 'Votre commande a été enregistrée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('erreur', 'Une erreur est survenue lors de la commande.');
        }
    }

    /**
     * Annuler une commande.
     */
    public function annuler(Commande $commande)
    {
        if ($commande->visiteur_id !== Auth::user()->visiteur->id) {
            abort(403);
        }

        if ($commande->statut !== 'en_attente') {
            return back()->with('erreur', 'Cette commande ne peut plus être annulée.');
        }

        $commande->update(['statut' => 'annulee']);

        return back()->with('succes', 'Commande annulée.');
    }
}

<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProduitControleur extends Controller
{
    /**
     * Liste des produits de l'artisan connecté.
     */
    public function lister()
    {
        $artisan = Auth::user()->artisan;
        $produits = $artisan->produits()->latest()->get();
        
        return Inertia::render('Artisan/Produits/Liste', [
            'produits' => $produits
        ]);
    }

    /**
     * Formulaire de création d'un produit.
     */
    public function formulaireCreation()
    {
        return Inertia::render('Artisan/Produits/Creation');
    }

    /**
     * Enregistrer un nouveau produit.
     */
    public function creer(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'categorie_produit' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_principale' => 'nullable|image|max:2048',
        ]);

        $artisan = Auth::user()->artisan;
        
        $produit = new Produit($request->except('image_principale'));
        $produit->artisan_id = $artisan->id;

        if ($request->hasFile('image_principale')) {
            $path = $request->file('image_principale')->store('produits', 'public');
            $produit->image_principale = $path;
        }

        $produit->save();

        return redirect()->route('artisan.produits.liste')
                         ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Formulaire de modification d'un produit.
     */
    public function formulaireModification(Produit $produit)
    {
        // Vérifier que le produit appartient bien à l'artisan
        if ($produit->artisan_id !== Auth::user()->artisan->id) {
            abort(403);
        }

        return Inertia::render('Artisan/Produits/Modification', [
            'produit' => $produit
        ]);
    }

    /**
     * Mettre à jour un produit.
     */
    public function modifier(Request $request, Produit $produit)
    {
        if ($produit->artisan_id !== Auth::user()->artisan->id) {
            abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'categorie_produit' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_principale' => 'nullable|image|max:2048',
        ]);

        $produit->fill($request->except('image_principale'));

        if ($request->hasFile('image_principale')) {
            $path = $request->file('image_principale')->store('produits', 'public');
            $produit->image_principale = $path;
        }

        $produit->save();

        return redirect()->route('artisan.produits.liste')
                         ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Supprimer un produit.
     */
    public function supprimer(Produit $produit)
    {
        if ($produit->artisan_id !== Auth::user()->artisan->id) {
            abort(403);
        }

        $produit->delete();

        return redirect()->route('artisan.produits.liste')
                         ->with('success', 'Produit supprimé.');
    }
}

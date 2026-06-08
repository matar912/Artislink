<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProduitControleur extends Controller
{
    /**
     * Liste des produits de l'artisan connecté.
     */
    public function lister()
    {
        $user = Auth::user();
        $artisan = $user->artisan;

        // Sécurité : Si le profil artisan est manquant, on le crée
        if (!$artisan) {
            $artisan = \App\Models\Artisan::create([
                'user_id' => $user->id,
                'categorie' => 'À définir',
            ]);
        }

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
        $user = Auth::user();
        
        // Sécurité : Vérifier l'existence du profil
        if (!$user->artisan) {
            \App\Models\Artisan::create([
                'user_id' => $user->id,
                'categorie' => 'À définir',
            ]);
        }

        return Inertia::render('Artisan/Produits/Creation');
    }

    /**
     * Enregistrer un nouveau produit.
     */
    public function creer(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'categorie_produit' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_principale' => 'nullable|image|max:2048',
            'est_disponible' => 'boolean',
        ]);

        $artisan = Auth::user()->artisan;
        
        $produit = new Produit($validated);
        $produit->artisan_id = $artisan->id;

        if ($request->hasFile('image_principale')) {
            $path = $request->file('image_principale')->store('produits/' . $artisan->id, 'public');
            $produit->image_principale = $path;
        }

        $produit->save();

        return redirect()->route('artisan.produits.liste')
                         ->with('succes', 'Le produit "' . $produit->nom . '" a été créé avec succès.');
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

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'categorie_produit' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_principale' => 'nullable|image|max:2048',
            'est_disponible' => 'boolean',
        ]);

        $produit->fill($validated);

        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image_principale) {
                Storage::disk('public')->delete($produit->image_principale);
            }
            $path = $request->file('image_principale')->store('produits/' . Auth::user()->artisan->id, 'public');
            $produit->image_principale = $path;
        }

        $produit->save();

        return redirect()->route('artisan.produits.liste')
                         ->with('succes', 'Le produit "' . $produit->nom . '" a été mis à jour.');
    }

    /**
     * Mettre à jour rapidement le stock d'un produit.
     */
    public function mettreAJourStock(Request $request, Produit $produit)
    {
        if ($produit->artisan_id !== Auth::user()->artisan->id) {
            abort(403);
        }

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $produit->update(['stock' => $validated['stock']]);

        return back()->with('succes', 'Stock mis à jour pour "' . $produit->nom . '".');
    }

    /**
     * Supprimer un produit.
     */
    public function supprimer(Produit $produit)
    {
        if ($produit->artisan_id !== Auth::user()->artisan->id) {
            abort(403);
        }

        if ($produit->image_principale) {
            Storage::disk('public')->delete($produit->image_principale);
        }

        $produit->delete();

        return redirect()->route('artisan.produits.liste')
                         ->with('succes', 'Le produit a été supprimé de votre catalogue.');
    }
}

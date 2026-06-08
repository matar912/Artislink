<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\PhotoArtisan;
use App\Models\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfilControleur extends Controller
{
    /**
     * Affiche le formulaire de modification du profil.
     */
    public function afficherFormulaire()
    {
        $user = Auth::user();
        
        // Charger l'artisan avec ses photos
        $artisan = Artisan::where('user_id', $user->id)->with('photos')->first();

        if (!$artisan) {
            // Créer le profil s'il manque (sécurité)
            $artisan = Artisan::create(['user_id' => $user->id, 'categorie' => 'Autre']);
        }

        return Inertia::render('Artisan/Profil/Modification', [
            'user' => $user,
            'artisan' => $artisan,
        ]);
    }

    /**
     * Sauvegarde les informations de profil.
     */
    public function sauvegarder(Request $request)
    {
        $user = Auth::user();
        $artisan = Artisan::where('user_id', $user->id)->first();

        if (!$artisan) {
            return back()->with('erreur', 'Profil artisan introuvable.');
        }

        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:25',
            'categorie' => 'required|string|max:100',
            'ville' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
        ]);

        // 1. Préparer les modifications pour l'utilisateur
        $user->fill([
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'telephone' => $validated['telephone'],
        ]);

        // 2. Préparer les modifications pour l'artisan
        $artisan->fill([
            'categorie' => $validated['categorie'],
            'ville' => $validated['ville'],
            'description' => $validated['description'],
        ]);

        // Vérifier si quelque chose a changé avant de sauvegarder
        $aChange = $user->isDirty() || $artisan->isDirty();

        if ($aChange) {
            $user->save();
            $artisan->save();
            return redirect()->route('artisan.tableau-bord')->with('succes', 'Votre profil a été mis à jour avec succès.');
        }

        // Si rien n'a changé, on redirige juste sans message de succès
        return redirect()->route('artisan.tableau-bord');
    }

    /**
     * Ajoute une photo à la galerie.
     */
    public function ajouterPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:3072', // 3MB max
            'legende' => 'nullable|string|max:200',
        ]);

        $artisan = Auth::user()->artisan;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('artisans/' . $artisan->id . '/galerie', 'public');

            PhotoArtisan::create([
                'artisan_id' => $artisan->id,
                'url' => $path,
                'legende' => $request->legende,
                'est_couverture' => $artisan->photos()->count() === 0,
            ]);
        }

        return back()->with('succes', 'Photo ajoutée à votre vitrine.');
    }

    /**
     * Supprime une photo de la galerie.
     */
    public function supprimerPhoto(PhotoArtisan $photo)
    {
        $artisan = Auth::user()->artisan;

        if ($photo->artisan_id !== $artisan->id) {
            abort(403);
        }

        Storage::disk('public')->delete($photo->url);
        $photo->delete();

        return back()->with('succes', 'La photo a été retirée.');
    }
}

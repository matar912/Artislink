<?php

namespace App\Http\Controllers\Auth;

// ============================================================
// FICHIER : app/Http/Controllers/Auth/ConnexionControleur.php
//
// RÔLE DE CE CONTRÔLEUR :
// Gère toute l'authentification du projet :
//   → Afficher les pages d'inscription (artisan / visiteur)
//   → Créer les comptes (avec le bon rôle automatiquement)
//   → Connecter / déconnecter un utilisateur
//
// LOGIQUE DU RÔLE :
//   L'utilisateur choisit sur la page d'accueil s'il est
//   artisan ou visiteur. Il est redirigé vers le bon formulaire.
//   Le champ "role" est défini ICI dans le contrôleur,
//   JAMAIS depuis le formulaire. C'est une sécurité importante.
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\User;
use App\Models\Visiteur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ConnexionControleur extends Controller
{
    // =========================================================
    // SECTION 1 : Affichage des pages
    // =========================================================

    /**
     * Page d'accueil du site.
     * Elle affiche les deux boutons : "Je suis artisan" et "Je suis visiteur"
     *
     * Route : GET /
     * Fichier Vue : resources/js/Pages/Accueil.vue
     */
    public function pageAccueil(): Response
    {
        return Inertia::render('Accueil');
    }

    public function dashboardArtisan(): Response
    {
        return Inertia::render('Artisan/Dashboard');
    }

    public function dashboardVisiteur(): Response
    {
        return Inertia::render('Visitor/Dashboard');
    }

    /**
     * Page de connexion (commune à tous les rôles).
     *
     * Route : GET /connexion
     * Fichier Vue : resources/js/Pages/Auth/Connexion.vue
     */
    public function pageConnexion(): Response
    {
        return Inertia::render('Auth/Connexion');
    }

    /**
     * Formulaire d'inscription pour les artisans.
     * L'utilisateur arrive ici après avoir cliqué "Je suis artisan".
     *
     * Route : GET /inscription/artisan
     * Fichier Vue : resources/js/Pages/Auth/InscriptionArtisan.vue
     */
    public function pageInscriptionArtisan(): Response
    {
        return Inertia::render('Auth/InscriptionArtisan');
    }

    /**
     * Formulaire d'inscription pour les visiteurs.
     * L'utilisateur arrive ici après avoir cliqué "Je suis visiteur".
     *
     * Route : GET /inscription/visiteur
     * Fichier Vue : resources/js/Pages/Auth/InscriptionVisiteur.vue
     */
    public function pageInscriptionVisiteur(): Response
    {
        return Inertia::render('Auth/InscriptionVisiteur');
    }

    // =========================================================
    // SECTION 2 : Traitement des inscriptions
    // =========================================================

    /**
     * Enregistre un nouveau compte artisan.
     *
     * Route : POST /inscription/artisan
     *
     * Ce qui se passe :
     *   1. On valide les données du formulaire
     *   2. On crée le user avec role = 'artisan' (défini ici, pas depuis le form)
     *   3. On crée le profil artisan lié à ce user
     *   4. On connecte automatiquement l'utilisateur
     *   5. On redirige vers son tableau de bord
     */
    public function inscrireArtisan(Request $request): RedirectResponse
    {
        // Étape 1 : Validation des données simplifiée
        $donnees = $request->validate([
            'nom'         => 'required|string|max:255',
            'prenom'      => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'mot_de_passe'=> ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'telephone'   => 'nullable|string|max:20',
            'categorie'   => 'required|string|max:100',
            'description' => 'nullable|string|max:2000',
            'ville'       => 'nullable|string|max:100',
        ]);

        // Étape 2 : Créer le user
        $utilisateur = User::create([
            'nom'       => $donnees['nom'],
            'prenom'    => $donnees['prenom'],
            'email'     => $donnees['email'],
            'password'  => Hash::make($donnees['mot_de_passe']),
            'role'      => 'artisan',
            'telephone' => $donnees['telephone'] ?? null,
        ]);

        // Étape 3 : Créer le profil artisan lié (champs simplifiés)
        Artisan::create([
            'user_id'     => $utilisateur->id,
            'categorie'   => $donnees['categorie'],
            'description' => $donnees['description'] ?? null,
            'ville'       => $donnees['ville'] ?? null,
        ]);

        // Étape 4 : Rediriger vers la page de connexion
        return redirect()
            ->route('connexion')
            ->with('succes', 'Bienvenue sur Artislink ! Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');
    }

    /**
     * Enregistre un nouveau compte visiteur.
     *
     * Route : POST /inscription/visiteur
     */
    public function inscrireVisiteur(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'nom'         => 'required|string|max:255',
            'prenom'      => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'mot_de_passe'=> ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'pays'        => 'nullable|string|max:100',
            'ville'       => 'nullable|string|max:100',
        ]);

        // Le rôle 'visiteur' est défini ici, côté serveur
        $utilisateur = User::create([
            'nom'      => $donnees['nom'],
            'prenom'   => $donnees['prenom'],
            'email'    => $donnees['email'],
            'password' => Hash::make($donnees['mot_de_passe']),
            'role'     => 'visiteur',   // ← TOUJOURS défini ici, côté serveur
        ]);

        Visiteur::create([
            'user_id' => $utilisateur->id,
            'pays'    => $donnees['pays'] ?? null,
            'ville'   => $donnees['ville'] ?? null,
        ]);

        return redirect()
            ->route('connexion')
            ->with('succes', 'Bienvenue sur Artislink ! Votre compte visiteur a été créé. Vous pouvez maintenant vous connecter.');
    }

    // =========================================================
    // SECTION 3 : Connexion et déconnexion
    // =========================================================

    /**
     * Traite le formulaire de connexion.
     *
     * Route : POST /connexion
     *
     * Ce qui se passe :
     *   1. On vérifie email + mot de passe
     *   2. On vérifie que le compte est actif
     *   3. On redirige selon le rôle (artisan → son tableau de bord, etc.)
     */
    public function connecter(Request $request): RedirectResponse
    {
        // Validation basique
        $identifiants = $request->validate([
            'email'       => 'required|email',
            'mot_de_passe'=> 'required',
        ]);

        // Tentative de connexion
        // Auth::attempt() vérifie email + mot de passe dans la base
        // Le 2ème paramètre = "se souvenir de moi"
        $connexionReussie = Auth::attempt([
            'email'    => $identifiants['email'],
            'password' => $identifiants['mot_de_passe'],
        ], $request->boolean('se_souvenir'));

        if (! $connexionReussie) {
            // Renvoie l'erreur au formulaire Vue
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        $utilisateur = Auth::user();

        // Vérifier que le compte est actif
        if (! $utilisateur->est_actif) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ]);
        }

        // Régénérer la session (sécurité contre les attaques de fixation de session)
        $request->session()->regenerate();

        // Rediriger vers le bon tableau de bord selon le rôle
        return match ($utilisateur->role) {
            'artisan' => redirect()->intended(route('artisan.tableau-bord')),
            'admin'   => redirect()->intended(route('admin.tableau-bord')),
            default   => redirect()->intended(route('visiteur.tableau-bord')),
        };
    }

    /**
     * Déconnecter l'utilisateur.
     *
     * Route : POST /deconnexion
     */
    public function deconnecter(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalider la session pour des raisons de sécurité
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('accueil');
    }
}

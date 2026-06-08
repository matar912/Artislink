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
use App\Models\Produit;
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
     * Elle affiche les produits réels, les artisans et les liens d'inscription.
     */
    public function pageAccueil(): Response
    {
        // On récupère les 4 derniers produits disponibles avec les infos de l'artisan
        $produits = Produit::with(['artisan.user'])
            ->where('est_disponible', true)
            ->latest()
            ->take(4)
            ->get();

        // On récupère les 4 derniers artisans inscrits pour la présentation
        $artisans = Artisan::with('user')
            ->latest()
            ->take(4)
            ->get();

        // Statistiques réelles pour le Hero
        $stats = [
            'total_artisans' => Artisan::count(),
            'total_produits' => Produit::count(),
        ];

        return Inertia::render('Accueil', [
            'produits' => $produits,
            'artisans' => $artisans,
            'stats'    => $stats,
        ]);
    }

    /**
     * Page de choix du type de compte (artisan ou visiteur).
     */
    public function pageChoixInscription(): Response
    {
        return Inertia::render('Auth/RegisterChoice');
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
            'est_actif' => true, // On active le compte immédiatement pour le prototype
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
            ->route('login')
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
            'role'     => 'visiteur',
            'est_actif'=> true, // Les visiteurs sont actifs par défaut
        ]);

        Visiteur::create([
            'user_id' => $utilisateur->id,
            'pays'    => $donnees['pays'] ?? null,
            'ville'   => $donnees['ville'] ?? null,
        ]);

        return redirect()
            ->route('login')
            ->with('succes', 'Bienvenue sur Artislink ! Votre compte visiteur a été créé. Vous pouvez maintenant vous connecter.');
    }

    // =========================================================
    // SECTION 3 : Connexion et déconnexion
    // =========================================================

    /**
     * Traite le formulaire de connexion avec un filtre strict par rôle.
     */
    public function connecter(Request $request): RedirectResponse
    {
        // Étape 1 : Validation des entrées
        $identifiants = $request->validate([
            'email'       => 'required|email',
            'mot_de_passe'=> 'required',
            'role'        => 'required|string|in:visiteur,artisan,admin',
        ]);

        // Étape 2 : Recherche de l'utilisateur par email (plus robuste)
        $emailNettoye = strtolower(trim($identifiants['email']));
        $utilisateur = User::where('email', $emailNettoye)->first();

        // Étape 3 : Vérification de l'existence et du mot de passe
        if (!$utilisateur || !Hash::check($identifiants['mot_de_passe'], $utilisateur->password)) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        // Étape 4 : FILTRE PAR RÔLE (Demande utilisateur)
        // On vérifie si le rôle en base de données correspond au rôle sélectionné dans l'onglet
        if ($utilisateur->role !== $identifiants['role']) {
            $typeSelectionne = match($identifiants['role']) {
                'artisan' => 'artisan',
                'admin'   => 'administrateur',
                default   => 'client',
            };
            
            $vraiRole = match($utilisateur->role) {
                'artisan' => 'un compte artisan',
                'admin'   => 'un compte administrateur',
                default   => 'un compte client',
            };

            return back()->withErrors([
                'email' => "Vous tentez de vous connecter en tant que {$typeSelectionne}, mais cet email est lié à {$vraiRole}.",
            ]);
        }

        // Étape 5 : Vérifier que le compte est actif
        if (! $utilisateur->est_actif) {
            return back()->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ]);
        }

        // Étape 6 : Tentative de connexion standard
        if (Auth::attempt(['email' => $identifiants['email'], 'password' => $identifiants['mot_de_passe']], $request->boolean('se_souvenir'))) {
            
            // Régénérer la session pour la sécurité
            $request->session()->regenerate();

            // Rediriger vers le bon tableau de bord (redirection explicite sans intended pour éviter les conflits de rôles)
            return match (Auth::user()->role) {
                'artisan' => redirect()->route('artisan.tableau-bord')->with('succes', 'Heureux de vous revoir !'),
                'admin'   => redirect()->route('admin.tableau-bord')->with('succes', 'Connexion admin réussie.'),
                default   => redirect()->route('visiteur.boutique')->with('succes', 'Bienvenue sur votre espace !'),
            };
        }

        // Si attempt échoue (cas improbable car password vérifié avant, mais sécurité supplémentaire)
        return back()->withErrors([
            'email' => 'Erreur lors de la création de la session.',
        ]);
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

        return redirect()->route('login')->with('succes', 'Vous avez été déconnecté.');
    }
}

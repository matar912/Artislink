<?php

use App\Http\Controllers\Admin\AdminControleur;
use App\Http\Controllers\Artisan\ProduitControleur;
use App\Http\Controllers\Artisan\ProfilControleur;
use App\Http\Controllers\Artisan\TableauBordControleur;
use App\Http\Controllers\Auth\ConnexionControleur;
use App\Http\Controllers\Auth\MotDePasseControleur;
use App\Http\Controllers\Partage\MessageControleur;
use App\Http\Controllers\Visiteur\AvisControleur;
use App\Http\Controllers\Visiteur\CommandeControleur;
use App\Http\Controllers\Visiteur\FavoriControleur;
use App\Http\Controllers\Visiteur\RechercheControleur;
use Illuminate\Support\Facades\Route;

// ============================================================
// ROUTES PUBLIQUES
// ============================================================

Route::get('/', [ConnexionControleur::class, 'pageAccueil'])->name('accueil');

Route::get('/dashboard', function () {
    return redirect(auth()->user()->dashboard_route);
})->middleware(['auth'])->name('dashboard');

Route::get('/artisans', [RechercheControleur::class, 'listerArtisans'])->name('artisans.liste');
Route::get('/artisans/{artisan}', [RechercheControleur::class, 'voirArtisan'])->name('artisans.voir');

// ============================================================
// ROUTES D'AUTHENTIFICATION (guests)
// ============================================================

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [ConnexionControleur::class, 'pageConnexion'])->name('login');
    Route::post('/connexion', [ConnexionControleur::class, 'connecter'])->name('connexion.traiter');
    
    Route::get('/inscription', [ConnexionControleur::class, 'pageChoixInscription'])->name('inscription');
    Route::get('/inscription/artisan', [ConnexionControleur::class, 'pageInscriptionArtisan'])->name('inscription.artisan');
    Route::post('/inscription/artisan', [ConnexionControleur::class, 'inscrireArtisan'])->name('inscription.artisan.traiter');
    Route::get('/inscription/visiteur', [ConnexionControleur::class, 'pageInscriptionVisiteur'])->name('inscription.visiteur');
    Route::post('/inscription/visiteur', [ConnexionControleur::class, 'inscrireVisiteur'])->name('inscription.visiteur.traiter');

    // ── Mot de passe oublié ──────────────────────────────────
    Route::get('/mot-de-passe-oublie', [MotDePasseControleur::class, 'formulaireLien'])
        ->name('password.request');

    Route::post('/mot-de-passe-oublie', [MotDePasseControleur::class, 'envoyerLien'])
        ->name('password.email');

    Route::get('/reinitialiser-mot-de-passe/{token}', [MotDePasseControleur::class, 'formulaireReinitialisation'])
        ->name('password.reset');

    Route::post('/reinitialiser-mot-de-passe', [MotDePasseControleur::class, 'reinitialiser'])
        ->name('password.update');
});

Route::post('/deconnexion', [ConnexionControleur::class, 'deconnecter'])->name('deconnexion')->middleware('auth');

// ============================================================
// ESPACE ARTISAN
// ============================================================

Route::middleware(['auth', 'role:artisan'])
    ->prefix('artisan')
    ->name('artisan.')
    ->group(function () {

    Route::get('/tableau-bord', [TableauBordControleur::class, 'afficher'])->name('tableau-bord');

    // Profil & Photos
    Route::get('/mon-profil', [ProfilControleur::class, 'afficherFormulaire'])->name('profil.formulaire');
    Route::put('/mon-profil', [ProfilControleur::class, 'sauvegarder'])->name('profil.sauvegarder');
    Route::post('/photos', [ProfilControleur::class, 'ajouterPhoto'])->name('photos.ajouter');
    Route::delete('/photos/{photo}', [ProfilControleur::class, 'supprimerPhoto'])->name('photos.supprimer');
    Route::patch('/photos/{photo}/couverture', [ProfilControleur::class, 'definirCouverture'])->name('photos.couverture');

    // ── Produits ─────────────────────────────────────────────
    Route::get('/mes-produits', [ProduitControleur::class, 'lister'])->name('produits.liste');
    Route::get('/mes-produits/nouveau', [ProduitControleur::class, 'formulaireCreation'])->name('produits.formulaire-creation');
    Route::post('/mes-produits', [ProduitControleur::class, 'creer'])->name('produits.creer');
    Route::get('/mes-produits/{produit}/modifier', [ProduitControleur::class, 'formulaireModification'])->name('produits.formulaire-modification');
    Route::put('/mes-produits/{produit}', [ProduitControleur::class, 'modifier'])->name('produits.modifier');
    Route::patch('/mes-produits/{produit}/stock', [ProduitControleur::class, 'mettreAJourStock'])->name('produits.stock');
    Route::delete('/mes-produits/{produit}', [ProduitControleur::class, 'supprimer'])->name('produits.supprimer');

    // ── Commandes (côté artisan) ─────────────────────────────
    Route::get('/mes-commandes', [TableauBordControleur::class, 'listerCommandes'])->name('commandes.liste');
    Route::patch('/commandes/{commande}/statut', [TableauBordControleur::class, 'changerStatut'])->name('commandes.statut');

    // Messagerie
    Route::get('/messages', [MessageControleur::class, 'listerConversations'])->name('messages.liste');
    Route::get('/messages/{utilisateur}', [MessageControleur::class, 'voirConversation'])->name('messages.conversation');
    Route::post('/messages/{utilisateur}', [MessageControleur::class, 'envoyer'])->name('messages.envoyer');
});

// ============================================================
// ESPACE VISITEUR
// ============================================================

Route::middleware(['auth', 'role:visiteur'])
    ->prefix('visiteur')
    ->name('visiteur.')
    ->group(function () {

    Route::get('/tableau-bord', [CommandeControleur::class, 'lister'])->name('tableau-bord');
    Route::get('/boutique', [RechercheControleur::class, 'boutique'])->name('boutique');
    Route::get('/panier', function () {
        return Inertia\Inertia::render('Visitor/Panier');
    })->name('panier');
    Route::get('/checkout', function () {
        return Inertia\Inertia::render('Visitor/Checkout');
    })->name('checkout');

    // ── Commandes ────────────────────────────────────────────
    Route::get('/mes-commandes', [CommandeControleur::class, 'lister'])->name('commandes.liste');
    Route::post('/commandes', [CommandeControleur::class, 'creer'])->name('commandes.creer');
    Route::patch('/commandes/{commande}/annuler', [CommandeControleur::class, 'annuler'])->name('commandes.annuler');

    // Avis, Favoris, Messagerie
    Route::post('/avis', [AvisControleur::class, 'creer'])->name('avis.creer');
    Route::put('/avis/{avis}', [AvisControleur::class, 'modifier'])->name('avis.modifier');
    Route::delete('/avis/{avis}', [AvisControleur::class, 'supprimer'])->name('avis.supprimer');

    Route::get('/mes-favoris', [FavoriControleur::class, 'lister'])->name('favoris.liste');
    Route::post('/favoris/{artisan}', [FavoriControleur::class, 'basculer'])->name('favoris.basculer');

    Route::get('/messages', [MessageControleur::class, 'listerConversations'])->name('messages.liste');
    Route::get('/messages/{utilisateur}', [MessageControleur::class, 'voirConversation'])->name('messages.conversation');
    Route::post('/messages/{utilisateur}', [MessageControleur::class, 'envoyer'])->name('messages.envoyer');
});

// ============================================================
// ESPACE ADMIN
// ============================================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    Route::get('/tableau-bord', [AdminControleur::class, 'tableauBord'])->name('tableau-bord');
    Route::get('/statistiques', [AdminControleur::class, 'statistiques'])->name('statistiques');
    Route::get('/utilisateurs', [AdminControleur::class, 'listerUtilisateurs'])->name('utilisateurs.liste');
    Route::patch('/utilisateurs/{utilisateur}/activer', [AdminControleur::class, 'basculerActivation'])->name('utilisateurs.activer');
    Route::delete('/utilisateurs/{utilisateur}', [AdminControleur::class, 'supprimerUtilisateur'])->name('utilisateurs.supprimer');
    Route::patch('/avis/{avis}/approuver', [AdminControleur::class, 'approuverAvis'])->name('avis.approuver');
    Route::delete('/avis/{avis}', [AdminControleur::class, 'supprimerAvis'])->name('avis.supprimer');
});

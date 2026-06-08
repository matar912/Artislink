<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminControleur extends Controller
{
    public function tableauBord()
    {
        $stats = [
            'total_utilisateurs' => User::count(),
            'total_artisans'     => Artisan::count(),
            'total_produits'     => Produit::count(),
            'total_commandes'    => Commande::count(),
            'derniers_utilisateurs' => User::latest()->take(5)->get(),
            'utilisateurs_en_attente' => User::where('est_actif', false)->with('artisan')->latest()->get(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }

    public function approuverUtilisateur(User $utilisateur)
    {
        $utilisateur->update(['est_actif' => true]);
        
        return back()->with('succes', 'L\'utilisateur ' . $utilisateur->name . ' a été approuvé avec succès.');
    }

    public function rejeterUtilisateur(User $utilisateur)
    {
        // On peut soit le supprimer, soit garder le compte en "rejeté" si on ajoute un champ statut.
        // Pour l'instant, la suppression est plus simple si on ne veut pas garder les données.
        $utilisateur->delete();
        
        return back()->with('succes', 'L\'inscription de l\'utilisateur a été rejetée et son compte supprimé.');
    }

    public function approuverAvis($avisId)
    {
        return back()->with('succes', 'Avis approuvé (simulation).');
    }

    public function supprimerAvis($avisId)
    {
        return back()->with('succes', 'Avis supprimé (simulation).');
    }
}

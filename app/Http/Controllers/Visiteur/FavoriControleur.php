<?php

namespace App\Http\Controllers\Visiteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriControleur extends Controller
{
    public function lister()
    {
        return Inertia::render('Visitor/Dashboard', [
            'message' => 'Vos favoris seront bientôt disponibles.'
        ]);
    }

    public function basculer($artisanId)
    {
        return back()->with('succes', 'Favori mis à jour (simulation).');
    }
}

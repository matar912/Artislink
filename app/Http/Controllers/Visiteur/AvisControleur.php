<?php

namespace App\Http\Controllers\Visiteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvisControleur extends Controller
{
    public function creer(Request $request)
    {
        return back()->with('succes', 'Avis publié (simulation).');
    }

    public function modifier(Request $request, $avisId)
    {
        return back()->with('succes', 'Avis modifié (simulation).');
    }

    public function supprimer($avisId)
    {
        return back()->with('succes', 'Avis supprimé (simulation).');
    }
}

<?php

namespace App\Http\Controllers\Partage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageControleur extends Controller
{
    public function listerConversations()
    {
        return Inertia::render('Dashboard', [
            'message' => 'La messagerie sera bientôt disponible.'
        ]);
    }

    public function voirConversation($utilisateurId)
    {
        return Inertia::render('Dashboard', [
            'message' => 'La conversation sera bientôt disponible.'
        ]);
    }

    public function envoyer(Request $request, $utilisateurId)
    {
        return back()->with('succes', 'Message envoyé (simulation).');
    }
}

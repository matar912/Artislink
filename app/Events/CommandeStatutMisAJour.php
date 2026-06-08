<?php

namespace App\Events;

use App\Models\Commande;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommandeStatutMisAJour implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande->load('visiteur.user');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->commande->visiteur->user_id),
        ];
    }
}

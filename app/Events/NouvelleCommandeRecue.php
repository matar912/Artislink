<?php

namespace App\Events;

use App\Models\Commande;
use App\Models\Artisan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NouvelleCommandeRecue implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commande;
    public $artisanId;

    public function __construct(Commande $commande, $artisanId)
    {
        $this->commande = $commande->load(['visiteur.user', 'produits' => function($q) use ($artisanId) {
            $q->where('artisan_id', $artisanId);
        }]);
        $this->artisanId = $artisanId;
    }

    public function broadcastOn(): array
    {
        $artisan = Artisan::find($this->artisanId);
        return [
            new PrivateChannel('user.' . $artisan->user_id),
        ];
    }
}

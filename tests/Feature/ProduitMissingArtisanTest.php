<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProduitMissingArtisanTest extends TestCase
{
    use RefreshDatabase;

    public function test_artisan_without_profile_record_gets_error_on_products_list(): void
    {
        // On crée un user avec le rôle artisan mais SANS le record dans la table 'artisans'
        $user = User::factory()->create([
            'role' => 'artisan',
            'est_actif' => true,
        ]);

        // Cette requête devrait échouer car ProduitControleur::lister() fait Auth::user()->artisan->produits
        $response = $this->actingAs($user)->get(route('artisan.produits.liste'));

        // On s'attend à une erreur 500 (ou que le test échoue avec une exception)
        $response->assertStatus(500);
    }
}

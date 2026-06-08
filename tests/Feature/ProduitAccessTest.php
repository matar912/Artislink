<?php

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProduitAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_artisan_can_access_products_list(): void
    {
        $user = User::factory()->create([
            'role' => 'artisan',
            'est_actif' => true,
        ]);

        Artisan::create([
            'user_id' => $user->id,
            'categorie' => 'Test',
        ]);

        $response = $this->actingAs($user)->get(route('artisan.produits.liste'));

        $response->assertStatus(200);
    }

    public function test_artisan_can_access_create_product_page(): void
    {
        $user = User::factory()->create([
            'role' => 'artisan',
            'est_actif' => true,
        ]);

        Artisan::create([
            'user_id' => $user->id,
            'categorie' => 'Test',
        ]);

        $response = $this->actingAs($user)->get(route('artisan.produits.formulaire-creation'));

        $response->assertStatus(200);
    }

    public function test_visitor_cannot_access_artisan_products_list(): void
    {
        $user = User::factory()->create([
            'role' => 'visiteur',
            'est_actif' => true,
        ]);

        $response = $this->actingAs($user)->get(route('artisan.produits.liste'));

        $response->assertStatus(403);
    }
}

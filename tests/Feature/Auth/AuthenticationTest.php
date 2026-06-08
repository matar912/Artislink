<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la page de connexion s'affiche correctement.
     */
    public function test_page_connexion_affichage(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    /**
     * Test qu'un utilisateur peut se connecter.
     */
    public function test_utilisateur_peut_se_connecter(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'role' => 'visiteur',
            'est_actif' => true,
        ]);

        $response = $this->post(route('connexion.traiter'), [
            'email' => $user->email,
            'mot_de_passe' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('boutique'));
    }

    /**
     * Test qu'un artisan est redirigé vers son propre tableau de bord.
     */
    public function test_artisan_redirige_vers_son_dashboard(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'role' => 'artisan',
            'est_actif' => true,
        ]);

        $response = $this->post(route('connexion.traiter'), [
            'email' => $user->email,
            'mot_de_passe' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('artisan.tableau-bord'));
    }

    /**
     * Test qu'on ne peut pas se connecter avec un mauvais mot de passe.
     */
    public function test_echec_connexion_mauvais_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('connexion.traiter'), [
            'email' => $user->email,
            'mot_de_passe' => 'mauvais-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test qu'un utilisateur désactivé ne peut pas se connecter.
     */
    public function test_utilisateur_desactive_ne_peut_pas_se_connecter(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'est_actif' => false,
        ]);

        $response = $this->post(route('connexion.traiter'), [
            'email' => $user->email,
            'mot_de_passe' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test que la déconnexion redirige vers la page de connexion.
     */
    public function test_deconnexion_redirige_vers_connexion(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('deconnexion'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}

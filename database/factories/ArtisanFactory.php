<?php

namespace Database\Factories;

use App\Models\Artisan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Artisan>
 */
class ArtisanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->artisan(),
            'categorie' => fake()->randomElement(['Potier', 'Tisserand', 'Sculpteur', 'Peintre', 'Forgeron']),
            'description' => fake()->paragraph(),
            'adresse' => fake()->address(),
            'ville' => fake()->city(),
            'region' => 'Dakar',
            'pays' => 'Sénégal',
            'note_moyenne' => 0,
            'nombre_avis' => 0,
            'est_verifie' => fake()->boolean(),
            'est_actif' => true,
        ];
    }
}

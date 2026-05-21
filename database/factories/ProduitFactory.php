<?php

namespace Database\Factories;

use App\Models\Artisan;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'artisan_id' => Artisan::factory(),
            'nom' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'prix' => fake()->randomFloat(2, 1000, 50000),
            'stock' => fake()->numberBetween(1, 50),
            'categorie_produit' => fake()->word(),
            'est_disponible' => true,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Visiteur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Visiteur>
 */
class VisiteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pays' => 'Sénégal',
            'ville' => fake()->city(),
            'preferences' => ['Potier', 'Tisserand'],
        ];
    }
}

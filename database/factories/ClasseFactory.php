<?php

namespace Database\Factories;

use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    protected $model = Classe::class;

    public function definition(): array
    {
        $niveaux = ['1AP', '2AP', '3AP', '4AP', '5AP', '6AP', '1AC', '2AC', '3AC'];
        $annees = ['2023-2024', '2024-2025'];
        
        return [
            'nom' => $this->faker->randomElement($niveaux) . ' ' . $this->faker->randomLetter(),
            'niveau' => $this->faker->randomElement($niveaux),
            'annee_scolaire' => $this->faker->randomElement($annees),
            'description' => $this->faker->optional()->sentence(),
            'professeur_principal' => $this->faker->optional()->name(),
            'effectif_max' => $this->faker->numberBetween(25, 40),
            'active' => $this->faker->boolean(80), // 80% chance d'être active
        ];
    }
} 
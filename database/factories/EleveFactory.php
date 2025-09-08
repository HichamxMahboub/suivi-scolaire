<?php

namespace Database\Factories;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Database\Eloquent\Factories\Factory;

class EleveFactory extends Factory
{
    protected $model = Eleve::class;

    public function definition(): array
    {
        $niveaux = ['1AP', '2AP', '3AP', '4AP', '5AP', '6AP', '1AC', '2AC', '3AC'];

        return [
            'numero_matricule' => 'MAT' . $this->faker->unique()->numberBetween(1000, 9999),
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'niveau_scolaire' => $this->faker->randomElement($niveaux),
            'date_naissance' => $this->faker->dateTimeBetween('-18 years', '-6 years'),
            'annee_entree' => $this->faker->numberBetween(2018, 2024),
            'adresse' => $this->faker->address(),
            'educateur_responsable' => $this->faker->name(),
            'sexe' => $this->faker->randomElement(['M', 'F']),
            'email' => $this->faker->optional()->safeEmail(),
            'telephone_parent' => $this->faker->optional()->phoneNumber(),
            'photo' => $this->faker->optional()->imageUrl(),
            'remarques' => $this->faker->optional()->text(),
            'redoublant' => $this->faker->optional()->boolean(),
            'niveau_redouble' => $this->faker->optional()->randomElement($niveaux),
            'annee_sortie' => $this->faker->optional()->numberBetween(2020, 2024),
            'cause_sortie' => $this->faker->optional()->sentence(),
            'classe_id' => Classe::factory(),
        ];
    }
}

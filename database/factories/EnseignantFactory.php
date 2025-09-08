<?php

namespace Database\Factories;

use App\Models\Encadrant;
use Illuminate\Database\Eloquent\Factories\Factory;

class EncadrantFactory extends Factory
{
    protected $model = Encadrant::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'matricule' => 'ENC' . $this->faker->unique()->numberBetween(1000, 9999),
            'telephone' => $this->faker->optional()->phoneNumber(),
            'adresse' => $this->faker->optional()->address(),
            'photo' => $this->faker->optional()->imageUrl(),
            'numero' => $this->faker->optional()->numerify('ENC-####'),
            'remarque' => $this->faker->optional()->sentence(),
        ];
    }
}

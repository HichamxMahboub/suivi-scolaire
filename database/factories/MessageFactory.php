<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use App\Models\Eleve;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $priorities = ['low', 'normal', 'high', 'urgent'];
        $types = ['general', 'academic', 'behavior', 'health', 'parent_contact', 'other'];
        $statuses = ['sent', 'delivered', 'read'];

        return [
            'sender_id' => User::factory(),
            'recipient_id' => User::factory(),
            'eleve_id' => $this->faker->optional()->randomElement([Eleve::factory(), null]),
            'subject' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement($priorities),
            'type' => $this->faker->randomElement($types),
            'status' => $this->faker->randomElement($statuses),
            'read_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
            'archived_at' => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

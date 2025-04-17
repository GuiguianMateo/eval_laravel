<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Salle;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'h_debut' => $this->faker->date(),
            'h_fin' => $this->faker->date(),
            'salle_id' => Salle::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
        ];
    }
}

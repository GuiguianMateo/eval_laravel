<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
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
        $salle = Salle::inRandomOrder()->first() ?? Salle::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $date = Carbon::now()->addDays(rand(1, 30))->startOfDay();

        // Réserver entre 8h et 18h
        $startHour = rand(8, 16);
        $h_debut = $date->copy()->setHour($startHour);
        $h_fin = $h_debut->copy()->addHours(rand(1, 3));

        // Vérifier que ça ne chevauche pas une réservation existante
        while (
            Reservation::where('salle_id', $salle->id)
                ->where(function ($query) use ($h_debut, $h_fin) {
                    $query->whereBetween('h_debut', [$h_debut, $h_fin])
                          ->orWhereBetween('h_fin', [$h_debut, $h_fin])
                          ->orWhere(function ($q) use ($h_debut, $h_fin) {
                              $q->where('h_debut', '<=', $h_debut)
                                ->where('h_fin', '>=', $h_fin);
                          });
                })->exists()
        ) {
            $date = Carbon::now()->addDays(rand(1, 30))->startOfDay();
            $startHour = rand(8, 16);
            $h_debut = $date->copy()->setHour($startHour);
            $h_fin = $h_debut->copy()->addHours(rand(1, 3));
        }

        return [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'h_debut' => $h_debut,
            'h_fin' => $h_fin,
        ];
    }
}

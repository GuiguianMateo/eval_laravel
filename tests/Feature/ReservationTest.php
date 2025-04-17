<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\salle;
use App\Models\reservation;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Tests pour RéservationController
    |--------------------------------------------------------------------------
    */

    // Non connecté

    public function test_non_connected_user_cannot_access_reservation_index()
    {
        $response = $this->get(route('reservation.index'));
        $response->assertRedirect(route('login'));
    }

    // Utilisateur sans rôle

    public function test_user_without_role_can_access_reservation_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('reservation.index'));
        $response->assertStatus(200);
    }


    public function test_user_without_role_can_access_reservation_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('reservation.create'));
        $response->assertStatus(200);
        $response->assertViewIs('reservation.create');
    }


    public function test_user_without_role_can_store_reservation()
    {
        $salle = salle::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'h_debut' => now(),
            'h_fin' => now()->addDays(2),
        ];

        $response = $this->post(route('reservation.store'), $data);
        $response->assertRedirect(route('reservation.index'));

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
        ]);
    }


    public function test_user_without_role_can_access_reservation_edit()
    {
        $salle = salle::factory()->create();
        $user = User::factory()->create();
        Bouncer::refresh();

        $this->actingAs($user);

        $reservation = reservation::factory()->create();

        $response = $this->get(route('reservation.edit', $reservation->id));
        $response->assertStatus(200);
        $response->assertViewIs('reservation.edit');
        $response->assertViewHas('reservation');
    }


    public function test_user_without_role_can_update_reservation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $salle = salle::factory()->create();
        $reservation = reservation::factory()->create();

        $data = [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'h_debut' => now(),
            'h_fin' => now()->addDays(3),
        ];

        $response = $this->put(route('reservation.update', $reservation->id), $data);
        $response->assertRedirect(route('reservation.index'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'h_debut' => now()->format('Y-m-d'),
            'h_fin' => now()->addDays(3)->format('Y-m-d'),
       ]);
    }


    public function test_user_without_role_can_destroy_reservation()
    {
        $salle = salle::factory()->create();
        $user = User::factory()->create();
        Bouncer::refresh();

        $this->actingAs($user);

        $reservation = reservation::factory()->create();

        $response = $this->delete(route('reservation.destroy', $reservation->id));
        $response->assertRedirect(route('reservation.index'));

        $this->assertSoftDeleted('reservations', [
            'id' => $reservation->id,
        ]);
    }


    public function test_user_without_role_can_restore_reservation()
    {
        $salle = salle::factory()->create();
        $user = User::factory()->create();
        Bouncer::refresh();

        $this->actingAs($user);

        $reservation = reservation::factory()->create();
        $reservation->delete();

        $response = $this->get(route('reservation.restore', $reservation->id));
        $response->assertRedirect(route('reservation.index'));
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
        ]);
    }
}


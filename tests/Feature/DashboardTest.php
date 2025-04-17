<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Salle;
use App\Models\Reservation;
use Carbon\Carbon;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_dashboard_with_correct_data()
    {
        // Création d'un utilisateur et authentification
        $user = User::factory()->create();
        $this->actingAs($user);

        // Création de salles
        $salle1 = Salle::factory()->create(['nom' => 'Salle A']);
        $salle2 = Salle::factory()->create(['nom' => 'Salle B']);

        // Création de réservations
        Reservation::factory()->create([
            'salle_id' => $salle1->id,
            'user_id' => $user->id,
            'h_debut' => Carbon::now()->subDays(1)->setTime(9, 0),
            'h_fin' => Carbon::now()->subDays(1)->setTime(11, 0),
        ]);

        Reservation::factory()->create([
            'salle_id' => $salle2->id,
            'user_id' => $user->id,
            'h_debut' => Carbon::now()->setTime(14, 0),
            'h_fin' => Carbon::now()->setTime(16, 0),
        ]);

        // Appel de la route du tableau de bord
        $response = $this->get('/dashboard');

        // Vérification du statut de la réponse
        $response->assertStatus(200);

        // Vérification des données passées à la vue
        $response->assertViewHasAll([
            'totalReservations',
            'totalSalles',
            'totalUsers',
            'recentReservations',
            'salleOccupancy',
            'reservationsByWeekday',
            'reservationsByMonth',
        ]);

        // Vérification des valeurs spécifiques
        $viewData = $response->viewData();

        $this->assertEquals(2, $viewData['totalReservations']);
        $this->assertEquals(2, $viewData['totalSalles']);
        $this->assertEquals(1, $viewData['totalUsers']);
        $this->assertCount(2, $viewData['recentReservations']);
        $this->assertCount(2, $viewData['salleOccupancy']);
        $this->assertCount(7, $viewData['reservationsByWeekday']);
        $this->assertCount(12, $viewData['reservationsByMonth']);
    }

    /** @test */
public function it_calculates_salle_occupancy_correctly()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $salle = Salle::factory()->create(['nom' => 'Salle C']);

    // Création de réservations totalisant 6 heures
    Reservation::factory()->create([
        'salle_id' => $salle->id,
        'user_id' => $user->id,
        'h_debut' => Carbon::now()->subDays(2)->setTime(8, 0),
        'h_fin' => Carbon::now()->subDays(2)->setTime(11, 0),
    ]);

    Reservation::factory()->create([
        'salle_id' => $salle->id,
        'user_id' => $user->id,
        'h_debut' => Carbon::now()->subDays(1)->setTime(13, 0),
        'h_fin' => Carbon::now()->subDays(1)->setTime(16, 0),
    ]);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);

    $occupancy = $response->viewData('salleOccupancy');

    $expectedRate = round((6 / (30 * 12)) * 100, 2);

    $this->assertEquals($expectedRate, $occupancy[0]['occupancy_rate']);
}


/** @test */
public function it_counts_reservations_by_weekday_correctly()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $salle = Salle::factory()->create();

    // Création de réservations pour chaque jour de la semaine
    foreach (range(0, 6) as $i) {
        Reservation::factory()->create([
            'salle_id' => $salle->id,
            'user_id' => $user->id,
            'h_debut' => Carbon::now()->startOfWeek()->addDays($i)->setTime(10, 0),
            'h_fin' => Carbon::now()->startOfWeek()->addDays($i)->setTime(12, 0),
        ]);
    }

    $response = $this->get('/dashboard');
    $response->assertStatus(200);

    $weekdayData = $response->viewData('reservationsByWeekday');

    foreach ($weekdayData as $dayData) {
        $this->assertEquals(1, $dayData['count']);
    }
}


/** @test */
public function it_counts_reservations_by_month_correctly()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $salle = Salle::factory()->create();

    // Création de réservations pour chaque mois de l'année
    foreach (range(1, 12) as $month) {
        Reservation::factory()->create([
            'salle_id' => $salle->id,
            'user_id' => $user->id,
            'h_debut' => Carbon::create(null, $month, 15, 10),
            'h_fin' => Carbon::create(null, $month, 15, 12),
        ]);
    }

    $response = $this->get('/dashboard');
    $response->assertStatus(200);

    $monthData = $response->viewData('reservationsByMonth');

    foreach ($monthData as $month) {
        $this->assertEquals(1, $month['count']);
    }
}


}

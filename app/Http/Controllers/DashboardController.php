<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salle;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReservations = Reservation::count();

        $totalSalles = Salle::count();

        $totalUsers = User::count();

        $recentReservations = Reservation::with(['user', 'salle'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $salleOccupancy = $this->getsalleOccupancy();

        $reservationsByWeekday = $this->getReservationsByWeekday();

        $reservationsByMonth = $this->getReservationsByMonth();

        // dd($salleOccupancy, $reservationsByWeekday, $reservationsByMonth);
        return view('dashboard', compact(
            'totalReservations',
            'totalSalles',
            'totalUsers',
            'recentReservations',
            'salleOccupancy',
            'reservationsByWeekday',
            'reservationsByMonth'
        ));

    }

    private function getsalleOccupancy()
    {
        // Calculer le taux d'occupation pour chaque salle
        $salles = Salle::withCount('reservations')->get();

        $salleOccupancy = [];
        foreach ($salles as $salle) {

            $totalReservationsHours = Reservation::where('salle_id', $salle->id)
                ->sum(DB::raw('TIMESTAMPDIFF(HOUR, h_debut, h_fin)'));

            $totalPossibleHours = 30 * 12;

            $occupancyRate = ($totalPossibleHours > 0)
                ? round(($totalReservationsHours / $totalPossibleHours) * 100, 2)
                : 0;

            $salleOccupancy[] = [
                'name' => $salle->nom,
                'reservations_count' => $salle->reservations_count,
                'occupancy_rate' => $occupancyRate
            ];
        }

        return $salleOccupancy;
    }

    private function getReservationsByWeekday()
    {
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $counts = array_fill(0, 7, 0);

        $reservations = Reservation::select(
            DB::raw('DAYOFWEEK(h_debut) as weekday'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('weekday')
        ->get();

        foreach ($reservations as $reservation) {

            $index = ($reservation->weekday + 5) % 7;
            $counts[$index] = $reservation->count;
        }

        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $result[] = [
                'day' => $days[$i],
                'count' => $counts[$i]
            ];
        }

        return $result;
    }

    private function getReservationsByMonth()
    {
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $currentYear = Carbon::now()->year;

        $reservations = Reservation::select(
            DB::raw('MONTH(h_debut) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('h_debut', $currentYear)
        ->groupBy('month')
        ->get();

        $counts = array_fill(0, 12, 0);

        foreach ($reservations as $reservation) {
            $counts[$reservation->month - 1] = $reservation->count;
        }

        $result = [];
        for ($i = 0; $i < 12; $i++) {
            $result[] = [
                'month' => $months[$i],
                'count' => $counts[$i]
            ];
        }

        return $result;
    }
}

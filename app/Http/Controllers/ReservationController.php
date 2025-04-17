<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::withTrashed()->get();

        return view('reservation.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reservations = Reservation::all();
        $salles = Salle::all();
        $users = User::all();

        return view('reservation.create', compact('reservations', 'salles', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $reservation = new Reservation();

        $reservation->h_debut = $data['h_debut'];
        $reservation->h_fin = $data['h_fin'];
        $reservation->salle_id = $data['salle_id'];
        $reservation->user_id = $data['user_id'];

        $reservation->save();
        session()->flash('message', ['type' => 'success', 'text' => __('Réservation créée avec succès.')]);

        return redirect()->route('reservation.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        session()->flash('message', ['type' => 'success', 'text' => __('Reservation supprimée avec succès.')]);

        return redirect()->route('reservation.index');
    }

    public function restore($id)
    {
        $reservation = Reservation::withTrashed()->findOrFail($id);
        $reservation->restore();

        session()->flash('message', ['type' => 'success', 'text' => __('Reservation restaurée avec succès.')]);

        return redirect()->route('reservation.index');
    }
}

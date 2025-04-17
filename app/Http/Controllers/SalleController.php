<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salles = Salle::withTrashed()->get();

        return view('salle.index', compact('salles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salles = Salle::all();

        return view('salle.create', compact('salles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $salle = new Salle();

        $salle->nom = $data['nom'];
        $salle->capacite = $data['capacite'];
        $salle->surface = $data['surface'];

        $salle->save();
        session()->flash('message', ['type' => 'success', 'text' => __('Salle créée avec succès.')]);

        return redirect()->route('salle.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salle $salle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salle $salle)
    {
        return view('salle.edit', compact('salle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salle $salle)
    {
        $data = $request->all();
        $salle = Salle::findOrFail($salle->id);

        $salle->nom = $data['nom'];
        $salle->capacite = $data['capacite'];
        $salle->surface = $data['surface'];

        $salle->save();

        session()->flash('message', ['type' => 'success', 'text' => __('Salle modifiée avec succès.')]);

        return redirect()->route('salle.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salle $salle)
    {
        $salle->delete();
        session()->flash('message', ['type' => 'success', 'text' => __('Salle supprimée avec succès.')]);

        return redirect()->route('salle.index');
    }

    public function restore($id)
    {
        $salle = Salle::withTrashed()->findOrFail($id);
        $salle->restore();

        session()->flash('message', ['type' => 'success', 'text' => __('Salle restaurée avec succès.')]);

        return redirect()->route('salle.index');
    }
}

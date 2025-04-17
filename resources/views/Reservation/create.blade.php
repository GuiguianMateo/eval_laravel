@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 min-h-screen p-6">
        <div class="w-11/12 max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold mb-4">{{ __("Réserver une salle") }}</h1>

            <form action="{{ route('reservation.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="h_debut" class="block font-medium text-gray-700 mt-4">{{ __("Heure de début") }}</label>

                    <input type="datetime-local" class="w-full mt-2 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" name="h_debut">
                    @error("h_debut")
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="h_fin" class="block font-medium text-gray-700 mt-4">{{ __("Nombre de place assise") }}</label>

                    <input type="datetime-local" aria-valuemin="0" aria-valuemax="100" class="w-full mt-2 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" name="h_fin">
                    @error("h_fin")
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="salle_id" class="block font-medium text-gray-700">{{ __("Choisir la salle") }}</label>

                    <select class="w-full mt-2 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" name="salle_id" id="salle_id">
                        <option value="">{{ __("Veuillez choisir une salle") }}</option>

                        @foreach($salles as $salle)
                        <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                            {{ $salle->nom }} {{ $salle->capacite }} place assise sur {{ $salle->surface }} m²
                        </option>
                    @endforeach
                    </select>
                    @error('salle_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <input type="text" name="user_id" id="user_id" value="{{ Auth::user()->id }}" hidden>

                <!-- Bouton -->
                <div class="text-right">
                    <button type="submit"
                            class="px-6 py-2 rounded-full bg-gradient-to-r from-green-500 to-green-700 text-white font-semibold shadow mt-4 hover:from-green-600 hover:to-green-800 transition-all duration-200">
                        {{ __("Créer") }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

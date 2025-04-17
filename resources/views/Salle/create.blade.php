@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 min-h-screen p-6">
        <div class="w-11/12 max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold mb-4">{{ __("Ajouter une Salle") }}</h1>

            <form action="{{ route('salle.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nom" class="block font-medium text-gray-700 mt-4">{{ __("Nom de la Salle") }}</label>

                    <input type="text" class="w-full mt-2 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" name="nom">
                    @error("nom")
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacite" class="block font-medium text-gray-700 mt-4">{{ __("Nombre de place assise") }}</label>

                    <input type="number" aria-valuemin="0" aria-valuemax="100" class="w-full mt-2 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" name="capacite">
                    @error("capacite")
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="surface" class="block font-medium text-gray-700 mt-4">{{ __("Taille de la salle (m²)") }}</label>

                    <input type="number" aria-valuemin="0" aria-valuemax="100" class="w-full mt-2 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" name="surface">
                    @error("surface")
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

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

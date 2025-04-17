@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 p-6 min-h-screen">
        @if (session('message'))
            <div class="alert mt-4 p-4 rounded-lg shadow-md
                        @if(session('message')['type'] === 'success')
                            bg-green-50 text-green-800 border border-green-400
                        @elseif(session('message')['type'] === 'error')
                            bg-red-50 text-red-800 border border-red-400
                        @else
                            bg-gray-50 text-gray-800 border border-gray-300
                        @endif">
                {{ session('message')['text'] }}
            </div>
        @endif
        <div class="w-11/12 max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6 mt-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-extrabold text-gray-800">{{ __("Liste des Reservations") }}</h1>
                <a class="p-3 px-6 rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white font-semibold shadow hover:from-green-500 hover:to-green-700 transition-all duration-200"
                   href="{{ route('reservation.create') }}">{{ __("Ajouter une Reservation") }}</a>
            </div>

            <div class="overflow-hidden rounded-lg border border-gray-300 shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gradient-to-r from-gray-200 to-gray-300">
                        <tr class="text-gray-800">
                            <th class="py-3 px-4 border-b">{{ __("Heure de début") }}</th>
                            <th class="py-3 px-4 border-b">{{ __("Heure de fin") }}</th>
                            <th class="py-3 px-4 border-b">{{ __("Salle de reservation") }}</th>
                            <th class="py-3 px-4 border-b">{{ __("Nom de reservation") }}</th>
                            <th class="py-3 px-4 border-b text-center">{{ __("Actions") }}</th>
                        </tr>
                    </thead>
                    @php
                        $isAdmin = Auth::user()->is_admin ?? false;
                    @endphp

                    @forelse ($reservations as $reservation)
                        {{-- @if ($isAdmin || $reservation->user_id === Auth::id()) --}}
                            <tr class="hover:bg-gray-50 transition-all duration-150">
                                <td class="py-3 px-4">{{ $reservation->h_debut }}</td>
                                <td class="py-3 px-4">{{ $reservation->h_fin }}</td>
                                <td class="py-3 px-4">{{ $reservation->salle->nom }}</td>
                                <td class="py-3 px-4">{{ $reservation->user->identity }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex gap-2">
                                        @if ($reservation->deleted_at === null)
                                            <form action="{{ route('reservation.destroy', $reservation) }}" method="post" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-2 rounded bg-red-500 text-white shadow hover:bg-red-600 transition-all duration-200">
                                                    {{ __("Annuler") }}
                                                </button>
                                            </form>
                                        @else
                                            {{-- @if ($isAdmin || $reservation->user_id === Auth::id()) --}}
                                              <form action="{{ route('reservation.restore', $reservation) }}" method="post" class="inline">
                                                    @csrf
                                                    @method('GET')
                                                    <button type="submit"
                                                            class="px-3 py-2 rounded bg-purple-500 text-white shadow hover:bg-purple-600 transition-all duration-200">
                                                        {{ __("Restaurer") }}
                                                    </button>
                                                </form>
                                            {{-- @endif --}}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        {{-- @endif --}}
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 px-4 text-center text-gray-500">{{ __('Aucune Reservation trouvée.') }}</td>
                        </tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection

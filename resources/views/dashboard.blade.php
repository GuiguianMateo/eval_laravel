@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-blue-500 uppercase mb-1">Réservations</div>
                        <div class="text-xl font-bold text-gray-800">{{ $totalReservations }}</div>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-green-500 uppercase mb-1">Salles</div>
                        <div class="text-xl font-bold text-gray-800">{{ $totalSalles }}</div>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-cyan-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-cyan-500 uppercase mb-1">Utilisateurs</div>
                        <div class="text-xl font-bold text-gray-800">{{ $totalUsers }}</div>
                    </div>
                    <div class="text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Taux d'occupation par salle -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="border-b px-4 py-3">
                <h6 class="m-0 font-bold text-blue-500">Taux d'occupation par salle</h6>
            </div>
            <div class="p-4">
                <div class="h-64">
                    <canvas id="salleOccupancyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Réservations par jour de la semaine -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="border-b px-4 py-3">
                <h6 class="m-0 font-bold text-blue-500">Réservations par jour</h6>
            </div>
            <div class="p-4">
                <div class="h-64">
                    <canvas id="weekdayChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Réservations par mois -->
        <div class="lg:col-span-8 bg-white rounded-lg shadow-md mb-6">
            <div class="border-b px-4 py-3">
                <h6 class="m-0 font-bold text-blue-500">Réservations par mois</h6>
            </div>
            <div class="p-4">
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Réservations récentes -->
        <div class="lg:col-span-4 bg-white rounded-lg shadow-md mb-6">
            <div class="border-b px-4 py-3">
                <h6 class="m-0 font-bold text-blue-500">Réservations récentes</h6>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-4 py-2 border border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salle</th>
                                <th class="px-4 py-2 border border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentReservations as $reservation)
                            <tr>
                                <td class="px-4 py-2 border border-gray-200 whitespace-nowrap text-sm text-gray-600">{{ $reservation->user->identity }}</td>
                                <td class="px-4 py-2 border border-gray-200 whitespace-nowrap text-sm text-gray-600">{{ $reservation->salle->nom }}</td>
                                <td class="px-4 py-2 border border-gray-200 whitespace-nowrap text-sm text-gray-600">{{ Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Réservations par jour
    const weekdayCtx = document.getElementById('weekChart').getContext('2d');
    new Chart(weekdayCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($reservationsByWeekday, 'day')) !!},
            datasets: [{
                label: 'Nombre de réservations',
                data: {!! json_encode(array_column($reservationsByWeekday, 'count')) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Réservations par mois
    const monthCtx = document.getElementById('monthChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($reservationsByMonth, 'month')) !!},
            datasets: [{
                label: 'Réservations mensuelles',
                data: {!! json_encode(array_column($reservationsByMonth, 'count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.4)',
                borderColor: 'rgba(54, 162, 235, 1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Taux d’occupation des salles
    const salleCtx = document.getElementById('salleChart').getContext('2d');
    new Chart(salleCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($salleOccupancy, 'name')) !!},
            datasets: [{
                label: 'Taux d\'occupation (%)',
                data: {!! json_encode(array_column($salleOccupancy, 'occupancy_rate')) !!},
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    {{-- Welcome Banner --}}
    <div class="text-white p-6 rounded-lg mb-6 shadow flex justify-between items-center" style="background-color: #925fe2;">
        <div>
            <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!!</h1>
            <p class="text-sm">Always stay updated in your admin portal</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded shadow text-center">
            <h2 class="font-semibold">Total Schedules</h2>
            <p class="text-2xl font-bold">{{ $totalSchedules ?? 0 }}</p>
        </div>
        <div class="bg-green-100 p-4 rounded shadow text-center">
            <h2 class="font-semibold">Total Faculty</h2>
            <p class="text-2xl font-bold">{{ $totalFaculty ?? 0 }}</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow text-center">
            <h2 class="font-semibold">Total Rooms</h2>
            <p class="text-2xl font-bold">{{ $totalRooms ?? 0 }}</p>
        </div>
        <div class="bg-red-100 p-4 rounded shadow text-center">
            <h2 class="font-semibold">Total Sections</h2>
            <p class="text-2xl font-bold">{{ $totalSections ?? 0 }}</p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold mb-2">Analytics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Faculty Workload --}}
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Faculty Workload</h3>
                <canvas id="facultyWorkloadChart"></canvas>
            </div>

            {{-- Room Usage --}}
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Room Usage</h3>
                <canvas id="roomUsageChart"></canvas>
            </div>

            {{-- Delivery Mode Distribution --}}
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-2">Delivery Mode Distribution</h3>
                <canvas id="deliveryModeChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Faculty Workload Chart
    const facultyWorkloadCtx = document.getElementById('facultyWorkloadChart').getContext('2d');
    new Chart(facultyWorkloadCtx, {
        type: 'bar',
        data: {
            labels: @json($facultyWorkloads->pluck('user.name')),
            datasets: [{
                label: 'Number of Schedules',
                data: @json($facultyWorkloads->pluck('schedules_count')),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Room Usage Chart
    const roomUsageCtx = document.getElementById('roomUsageChart').getContext('2d');
    new Chart(roomUsageCtx, {
        type: 'bar',
        data: {
            labels: @json($roomOccupancy->pluck('room_no')),
            datasets: [{
                label: 'Number of Schedules',
                data: @json($roomOccupancy->pluck('schedules_count')),
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Delivery Mode Distribution Chart
    const deliveryModeCtx = document.getElementById('deliveryModeChart').getContext('2d');
    new Chart(deliveryModeCtx, {
        type: 'pie',
        data: {
            labels: @json($deliveryModes->pluck('delivery_mode')),
            datasets: [{
                label: 'Schedules',
                data: @json($deliveryModes->pluck('total')),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                ],
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Scheduling System') }}</title>

    {{-- App CSS --}}
    @vite('resources/css/app.css')

    {{-- FullCalendar CSS (v6+) --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    {{-- Optional Bootstrap Theme --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/bootstrap/main.min.css" rel="stylesheet">

    {{-- App JS --}}
    @vite('resources/js/app.js')
</head>
<body class="flex h-screen bg-gray-100">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6 overflow-auto">
        {{-- Current Date & Time --}}
        <div class="flex justify-end mb-4 text-gray-600">
            <span id="current-datetime" class="font-medium">
                {{ now()->format('l, F j, Y, h:i:s A') }}
            </span>
        </div>

        {{-- Page Content --}}
        @yield('content')
    </main>

    {{-- Alpine.js (for sidebar) --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Live Date & Time Script --}}
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('current-datetime').textContent = now.toLocaleString('en-US', options);
        }

        setInterval(updateDateTime, 1000);
        updateDateTime(); // initial call
    </script>

</body>
</html>

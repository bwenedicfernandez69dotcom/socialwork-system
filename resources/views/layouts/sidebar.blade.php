{{-- resources/views/components/sidebar.blade.php --}}
<aside 
    x-data="{ open: false }"
    @mouseenter="open = true" 
    @mouseleave="open = false"
    class="h-screen text-white flex flex-col transition-all duration-300"
    :class="open ? 'w-64' : 'w-16'"
    style="background-color: #925fe2;"
>
    <!-- Brand -->
    <div class="flex items-center justify-center h-24 border-b border-purple-600">
        {{-- Full logo when expanded --}}
        <img 
            x-show="open" 
            src="{{ asset('images/logo-full.png') }}" 
            alt="Social Work Logo" 
            class="h-20"
        >
        {{-- Icon/logo when collapsed --}}
        <img 
            x-show="!open" 
            src="{{ asset('images/logo-icon.png') }}" 
            alt="SW" 
            class="h-10"
        >
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
        {{-- Admin Links --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>🏠</span>
                <span x-show="open" class="ml-2">Dashboard</span>
            </a>
            <a href="{{ route('admin.faculties.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>👨‍🏫</span>
                <span x-show="open" class="ml-2">Faculties</span>
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>🏫</span>
                <span x-show="open" class="ml-2">Rooms</span>
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>📖</span>
                <span x-show="open" class="ml-2">Subjects</span>
            </a>
            <a href="{{ route('admin.sections.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>👥</span>
                <span x-show="open" class="ml-2">Sections</span>
            </a>
            <a href="{{ route('admin.schedules.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>📋</span>
                <span x-show="open" class="ml-2">Schedules</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>📝</span>
                <span x-show="open" class="ml-2">Reports</span>
            </a>
        @endif

        {{-- Scheduler Links --}}
        @if(Auth::user()->role === 'scheduler')
            <a href="{{ route('schedules.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>📋</span>
                <span x-show="open" class="ml-2">Schedules</span>
            </a>
            <a href="{{ route('schedules.timetable') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>⏱️</span>
                <span x-show="open" class="ml-2">Timetable</span>
            </a>
        @endif

        {{-- Faculty Links --}}
        @if(Auth::user()->role === 'faculty')
            @php
                $unreadCount = Auth::user()->unreadNotifications
                    ->where('type', 'App\Notifications\NewScheduleNotification')
                    ->filter(function ($n) {
                        $scheduleId = $n->data['schedule_id'] ?? null;
                        if (!$scheduleId) return false;

                        $schedule = \App\Models\Schedule::find($scheduleId);
                        return $schedule && $schedule->status === 'Pending';
                    })
                    ->count();
            @endphp

            <a href="{{ route('faculty.schedules.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>📅</span>
                <span x-show="open" class="ml-2">My Schedules</span>
            </a>
            <a href="{{ route('faculty.availabilities.index') }}" class="flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>⏰</span>
                <span x-show="open" class="ml-2">My Availabilities</span>
            </a>
            <a href="{{ route('faculty.notifications.index') }}" class="relative flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>🔔</span>
                <span x-show="open" class="ml-2">Notifications</span>

                {{-- Show badge only if there are pending unread notifications --}}
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-4 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        @endif
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t" style="border-color: rgba(255, 255, 255, 0.2);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center p-2 rounded hover:bg-purple-600/60">
                <span>🚪</span>
                <span x-show="open" class="ml-2">Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- Include Alpine.js if not already in app.blade --}}
<script src="//unpkg.com/alpinejs" defer></script>

@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Faculties</h1>
        <a href="{{ route('admin.faculties.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow-md text-sm font-semibold transition">
            + Add Faculty
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table Headers -->
    <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b bg-gray-50 text-gray-700 uppercase text-sm font-semibold rounded-t-lg">
        <div class="col-span-1">Employee ID</div>
        <div class="col-span-3">Name</div>
        <div class="col-span-3">Email</div>
        <div class="col-span-2">Department</div>
        <div class="col-span-2">Availabilities</div>
        <div class="col-span-1 text-center">Actions</div>
    </div>

    <!-- Faculty Rows -->
    <div class="mt-2 space-y-3">
        @forelse ($faculties as $faculty)
            <div class="grid grid-cols-12 gap-4 items-center 
                        bg-white rounded-full shadow-xl px-6 py-3 
                        hover:shadow-2xl transition">
                <!-- Employee ID -->
                <div class="col-span-1 font-medium text-gray-800">
                    {{ $faculty->employee_id }}
                </div>

                <!-- Name with Avatar -->
                <div class="col-span-3 flex items-center space-x-3">
                    @php
                        $name = $faculty->user->name ?? 'N/A';
                        $initial = strtoupper(mb_substr($name, 0, 1));
                        $colors = [
                            'bg-red-500','bg-yellow-500','bg-green-500',
                            'bg-blue-500','bg-indigo-500','bg-purple-500',
                            'bg-pink-500','bg-orange-500','bg-teal-500'
                        ];
                        $color = $colors[$faculty->id % count($colors)];
                    @endphp

                    <div class="w-10 h-10 flex items-center justify-center 
                                rounded-full text-white font-bold {{ $color }}">
                        {{ $initial }}
                    </div>
                    <span class="text-gray-700">{{ $name }}</span>
                </div>

                <!-- Email -->
                <div class="col-span-3 text-gray-700">
                    {{ $faculty->user->email ?? 'N/A' }}
                </div>

                <!-- Department -->
                <div class="col-span-2 text-gray-700">
                    {{ $faculty->department }}
                </div>

                <!-- Availabilities -->
                <div class="col-span-2">
                    <a href="{{ route('admin.faculties.availabilities.index', $faculty->id) }}" 
                       class="text-blue-600 hover:underline font-medium">
                       View Availabilities
                    </a>
                </div>

                <!-- Actions -->
                <div class="col-span-1 flex justify-center space-x-2">
                    <a href="{{ route('admin.faculties.edit', $faculty) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                        Edit
                    </a>
                    <form action="{{ route('admin.faculties.destroy', $faculty) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this faculty?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm text-sm font-medium transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-6 text-gray-500 bg-white rounded-lg shadow">
                No faculties found.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $faculties->links() }}
    </div>
</div>
@endsection

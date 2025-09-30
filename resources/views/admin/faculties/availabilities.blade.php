@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4">Availabilities of {{ $faculty->user->name ?? 'N/A' }}</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full border rounded shadow-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border px-4 py-2">Day</th>
                    <th class="border px-4 py-2">Start Time</th>
                    <th class="border px-4 py-2">End Time</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($availabilities as $availability)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-4 py-2">{{ $availability->day }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($availability->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">No availabilities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.faculties.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Faculties</a>
    </div>
</div>
@endsection

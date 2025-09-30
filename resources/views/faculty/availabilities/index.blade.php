@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Availabilities</h2>

    {{-- Success/Error messages --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add Availability --}}
    <div class="mb-4">
        <a href="{{ route('faculty.availabilities.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            + Add Availability
        </a>
    </div>

    {{-- Availabilities Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2 border">Day</th>
                    <th class="px-4 py-2 border">Start Time</th>
                    <th class="px-4 py-2 border">End Time</th>
                    <th class="px-4 py-2 border">Semester</th>
                    <th class="px-4 py-2 border">School Year</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($availabilities as $availability)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $availability->day }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}</td>
                        <td class="px-4 py-2 border">{{ $availability->semester }}</td>
                        <td class="px-4 py-2 border">{{ $availability->school_year }}</td>
                        <td class="px-4 py-2 border">
                            <span class="px-2 py-1 rounded text-sm 
                                {{ $availability->status === 'available' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ ucfirst($availability->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border space-x-2 flex items-center">
                            <a href="{{ route('faculty.availabilities.edit', $availability->id) }}" 
                               class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('faculty.availabilities.destroy', $availability->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this availability?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-4 py-2 border text-gray-500">
                            No availabilities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

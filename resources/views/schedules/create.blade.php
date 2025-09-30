@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Create Schedule</h2>

    <form action="{{ route('schedules.store') }}" method="POST">
        @csrf

        {{-- Include the reusable form --}}
        @include('schedules._form')

        {{-- Buttons --}}
        <div class="flex gap-2 mt-4">
            <a href="{{ route('schedules.index') }}" 
               class="px-6 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                Back
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-purple-700 text-white rounded hover:bg-purple-800">
                Create Schedule
            </button>
        </div>
    </form>
</div>
@endsection

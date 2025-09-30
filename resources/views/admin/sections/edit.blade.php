@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4">Edit Section</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sections.update', $section->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Section Name</label>
            <input type="text" name="name" value="{{ old('name', $section->name) }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Year Level</label>
            <input type="text" name="year_level" value="{{ old('year_level', $section->year_level) }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Program</label>
            <input type="text" name="program" value="{{ old('program', $section->program) }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        <div class="flex space-x-2">
            <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                Update
            </button>
            <a href="{{ route('admin.sections.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

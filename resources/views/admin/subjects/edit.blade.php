@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded shadow-md max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Subject</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.subjects.update', $subject->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Subject Title --}}
        <div>
            <label class="block text-gray-700 font-medium">Subject Title</label>
            <input type="text" name="title" value="{{ old('title', $subject->title) }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        {{-- Subject Code --}}
        <div>
            <label class="block text-gray-700 font-medium">Subject Code</label>
            <input type="text" name="code" value="{{ old('code', $subject->code) }}"
                   class="w-full border px-3 py-2 rounded" required>
        </div>

        {{-- Units --}}
        <div>
            <label class="block text-gray-700 font-medium">Units</label>
            <input type="number" name="units" value="{{ old('units', $subject->units) }}"
                   class="w-full border px-3 py-2 rounded" required min="1">
        </div>

        {{-- Year Level --}}
        <div>
            <label class="block text-gray-700 font-medium">Year Level</label>
            <select name="year_level" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Select Year Level --</option>
                @foreach([1,2,3,4] as $year)
                    <option value="{{ $year }}" {{ old('year_level', $subject->year_level) == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Semester --}}
        <div>
            <label class="block text-gray-700 font-medium">Semester</label>
            <select name="semester" class="w-full border px-3 py-2 rounded" required>
                <option value="">-- Select Semester --</option>
                <option value="1st" {{ old('semester', $subject->semester) == '1st' ? 'selected' : '' }}>1st</option>
                <option value="2nd" {{ old('semester', $subject->semester) == '2nd' ? 'selected' : '' }}>2nd</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex space-x-2">
            <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                Update
            </button>
            <a href="{{ route('admin.subjects.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

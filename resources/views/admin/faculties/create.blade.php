@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Create New Faculty</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.faculties.store') }}" method="POST">
        @csrf

        {{-- Full Name --}}
        <div class="mb-3">
            <label class="block text-sm font-medium">Full Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}" placeholder="Enter full name">
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}" placeholder="Enter email">
        </div>

        {{-- Employee ID --}}
        <div class="mb-3">
            <label class="block text-sm font-medium">Employee ID</label>
            <input type="text" name="employee_id" class="w-full border rounded p-2" value="{{ old('employee_id') }}" placeholder="Enter employee ID">
        </div>

        {{-- Department --}}
        <div class="mb-3">
            <label class="block text-sm font-medium">Department</label>
            <input type="text" name="department" class="w-full border rounded p-2" value="{{ old('department') }}" placeholder="Enter department">
        </div>

        {{-- Max Load --}}
        <div class="mb-3">
            <label class="block text-sm font-medium">Max Load</label>
            <input type="number" name="max_load" class="w-full border rounded p-2" value="{{ old('max_load', 5) }}" placeholder="Enter max load">
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <a href="{{ route('admin.faculties.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create</button>
        </div>
    </form>
</div>
@endsection

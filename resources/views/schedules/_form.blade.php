{{-- Display general errors --}}
@if($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Section --}}
<div class="mb-4">
    <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
    <select name="section_id" id="section_id" 
            class="mt-1 block w-full border rounded p-2 @error('section_id') border-red-500 @enderror">
        <option value="">-- Select Section --</option>
        @foreach($sections as $section)
            <option value="{{ $section->id }}"
                {{ old('section_id', $schedule->section_id ?? '') == $section->id ? 'selected' : '' }}>
                {{ $section->name }} - {{ $section->year_level }} ({{ $section->program }})
            </option>
        @endforeach
    </select>
    @error('section_id')
        <p class="text-red-600 text-sm">{{ $message }}</p>
    @enderror
</div>

{{-- Year Level --}}
<div class="mb-4">
    <label class="block font-medium mb-1">Year Level</label>
    <select id="year_level" name="year_level" 
            class="w-full border rounded px-3 py-2 @error('year_level') border-red-500 @enderror">
        <option value="">-- Select Year Level --</option>
        <option value="1" {{ old('year_level', $schedule->year_level ?? '') == 1 ? 'selected' : '' }}>1st Year</option>
        <option value="2" {{ old('year_level', $schedule->year_level ?? '') == 2 ? 'selected' : '' }}>2nd Year</option>
        <option value="3" {{ old('year_level', $schedule->year_level ?? '') == 3 ? 'selected' : '' }}>3rd Year</option>
        <option value="4" {{ old('year_level', $schedule->year_level ?? '') == 4 ? 'selected' : '' }}>4th Year</option>
    </select>
    @error('year_level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Semester --}}
<div class="mb-4">
    <label class="block font-medium mb-1">Semester</label>
    <select id="semester" name="semester" 
            class="w-full border rounded px-3 py-2 @error('semester') border-red-500 @enderror">
        <option value="">-- Select Semester --</option>
        <option value="1st" {{ old('semester', $schedule->semester ?? '') == '1st' ? 'selected' : '' }}>1st</option>
        <option value="2nd" {{ old('semester', $schedule->semester ?? '') == '2nd' ? 'selected' : '' }}>2nd</option>
    </select>
    @error('semester')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Subject (dynamic) --}}
<div class="mb-4">
    <label class="block font-medium mb-1">Subject</label>
    <select id="subject_id" name="subject_id" 
            class="w-full border rounded px-3 py-2 @error('subject_id') border-red-500 @enderror">
        <option value="">-- Select Subject --</option>
        {{-- dynamically loaded via JS --}}
    </select>
    @error('subject_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Room --}}
<div class="mb-4">
    <label class="block font-medium mb-1">Room</label>
    <select name="room_id" id="room_id" 
            class="w-full border rounded px-3 py-2 @error('room_id') border-red-500 @enderror">
        <option value="">-- Select Room --</option>
        @foreach($rooms as $room)
            <option value="{{ $room->id }}" {{ old('room_id', $schedule->room_id ?? '') == $room->id ? 'selected' : '' }}>
                {{ $room->room_no }} ({{ $room->booking_count }} booking{{ $room->booking_count != 1 ? 's' : '' }})
            </option>
        @endforeach
    </select>
    @error('room_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Faculty --}}
<div class="mb-4">
    <label for="faculty_id" class="block text-sm font-medium text-gray-700">Faculty</label>
    <select name="faculty_id" id="faculty_id" 
            class="mt-1 block w-full border rounded p-2 @error('faculty_id') border-red-500 @enderror">
        <option value="">-- Select Faculty --</option>
        @foreach($faculties as $faculty)
            <option value="{{ $faculty->id }}"
                {{ old('faculty_id', $schedule->faculty_id ?? '') == $faculty->id ? 'selected' : '' }}>
                {{ $faculty->user->name }} ({{ $faculty->current_load }}/{{ $faculty->max_load }})
            </option>
        @endforeach
    </select>
    @error('faculty_id')
        <p class="text-red-600 text-sm">{{ $message }}</p>
    @enderror
</div>

{{-- Delivery Mode --}}
<div class="mb-4">
    <label class="block font-medium mb-1">Delivery Mode</label>
    <select name="delivery_mode" id="delivery_mode" 
            class="w-full border rounded px-3 py-2 @error('delivery_mode') border-red-500 @enderror">
        <option value="">-- Select Mode --</option>
        <option value="Face-to-Face" {{ old('delivery_mode', $schedule->delivery_mode ?? '') == 'Face-to-Face' ? 'selected' : '' }}>Face-to-Face</option>
        <option value="Online" {{ old('delivery_mode', $schedule->delivery_mode ?? '') == 'Online' ? 'selected' : '' }}>Online</option>
    </select>
    @error('delivery_mode')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- School Year --}}
<div class="mb-4">
    <label class="block font-medium mb-1">School Year</label>
    <input type="text" name="school_year" placeholder="2025-2026" 
           class="w-full border rounded px-3 py-2 @error('school_year') border-red-500 @enderror" 
           value="{{ old('school_year', $schedule->school_year ?? '') }}">
    @error('school_year')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Days --}}
<div class="mb-4">
    <label class="block font-medium mb-1">Days</label>
    <div class="flex flex-wrap gap-2">
        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
            <label class="inline-flex items-center">
                <input type="checkbox" name="days[]" value="{{ $day }}" 
                       {{ in_array($day, old('days', isset($schedule) ? explode(',', $schedule->days) : [])) ? 'checked' : '' }} 
                       class="mr-1">
                {{ $day }}
            </label>
        @endforeach
    </div>
    @error('days')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
</div>

{{-- Start & End Time --}}
<div class="mb-4 grid grid-cols-2 gap-4">
    <div>
        <label class="block font-medium mb-1">Start Time</label>
        <input type="time" name="start_time" 
               class="w-full border rounded px-3 py-2 @error('start_time') border-red-500 @enderror" 
               value="{{ old('start_time', $schedule->start_time ?? '') }}">
        @error('start_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block font-medium mb-1">End Time</label>
        <input type="time" name="end_time" 
               class="w-full border rounded px-3 py-2 @error('end_time') border-red-500 @enderror" 
               value="{{ old('end_time', $schedule->end_time ?? '') }}">
        @error('end_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Scripts --}}
<script>
    // Disable Room if Online
    document.addEventListener('DOMContentLoaded', function () {
        const deliveryMode = document.getElementById('delivery_mode');
        const roomSelect = document.getElementById('room_id');

        function toggleRoom() {
            if (deliveryMode.value === 'Online') {
                roomSelect.disabled = true;
                roomSelect.value = "";
            } else {
                roomSelect.disabled = false;
            }
        }

        deliveryMode.addEventListener('change', toggleRoom);
        toggleRoom();
    });

    // Load subjects dynamically based on Year + Semester
    document.addEventListener('DOMContentLoaded', function () {
        const yearSelect = document.getElementById('year_level');
        const semSelect = document.getElementById('semester');
        const subjectSelect = document.getElementById('subject_id');
        const filterUrl = "{{ route('schedules.subjects.filter') }}"; // ✅ FIXED ROUTE

        function loadSubjects() {
            let year = yearSelect.value;
            let sem = semSelect.value;

            if (year && sem) {
                fetch(`${filterUrl}?year_level=${year}&semester=${sem}`)
                    .then(res => res.json())
                    .then(data => {
                        subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
                        data.forEach(subject => {
                            subjectSelect.innerHTML += `<option value="${subject.id}">${subject.title}</option>`;
                        });

                        // keep selected subject if editing
                        @if(old('subject_id', $schedule->subject_id ?? false))
                            subjectSelect.value = "{{ old('subject_id', $schedule->subject_id ?? '') }}";
                        @endif
                    })
                    .catch(err => {
                        console.error("Error fetching subjects:", err);
                        subjectSelect.innerHTML = '<option value="">-- Error loading subjects --</option>';
                    });
            } else {
                subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
            }
        }

        yearSelect.addEventListener('change', loadSubjects);
        semSelect.addEventListener('change', loadSubjects);

        // auto-load if editing existing schedule
        if (yearSelect.value && semSelect.value) {
            loadSubjects();
        }
    });
</script>

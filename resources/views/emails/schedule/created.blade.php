@component('mail::message')
# New Schedule Assigned

Hello {{ $schedule->faculty->name }},

You have been assigned a new schedule:

- **Subject:** {{ $schedule->subject->title }}
- **Section:** {{ $schedule->section->name }}
- **Day/Time:** {{ $schedule->days }} - {{ $schedule->start_time }} to {{ $schedule->end_time }}
- **Room:** {{ $schedule->room->room_no }}

@component('mail::button', ['url' => route('faculty.schedules.index')])
View My Schedule
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

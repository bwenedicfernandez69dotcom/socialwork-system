<?php

namespace App\Notifications;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewScheduleNotification extends Notification
{
    use Queueable;

    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Determine notification channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Store in DB + send email
    }

    /**
     * Store notification in database.
     */
    public function toDatabase($notifiable)
    {
        $facultyName   = $this->schedule->faculty?->user?->name ?? 'Assigned Faculty';
        $subjectTitle  = $this->schedule->subject?->title ?? 'Subject';
        $sectionName   = $this->schedule->section?->name ?? '';
        $roomNo        = $this->schedule->room?->room_no ?? 'TBA';
        $days          = $this->schedule->days;
        $start         = date('H:i', strtotime($this->schedule->start_time));
        $end           = date('H:i', strtotime($this->schedule->end_time));
        $deliveryMode  = $this->schedule->delivery_mode ?? 'TBA';

        return [
            'schedule_id'   => $this->schedule->id,
            'faculty_name'  => $facultyName,
            'subject'       => $subjectTitle,
            'section'       => $sectionName,
            'room'          => $roomNo,
            'days'          => $days,
            'time'          => "{$start}-{$end}",
            'delivery_mode' => $deliveryMode,
            'status'        => 'Assigned',
            'message'       => "New schedule automatically assigned: {$subjectTitle} ({$days} {$start}-{$end}) in Room {$roomNo}, Section {$sectionName}, Delivery: {$deliveryMode}.",
        ];
    }

    /**
     * Send email notification.
     */
    public function toMail($notifiable)
    {
        $facultyName   = $this->schedule->faculty?->user?->name ?? 'Faculty';
        $subjectTitle  = $this->schedule->subject?->title ?? 'Subject';
        $sectionName   = $this->schedule->section?->name ?? '';
        $roomNo        = $this->schedule->room?->room_no ?? 'TBA';
        $days          = $this->schedule->days;
        $start         = date('H:i', strtotime($this->schedule->start_time));
        $end           = date('H:i', strtotime($this->schedule->end_time));
        $deliveryMode  = $this->schedule->delivery_mode ?? 'TBA';

        return (new MailMessage)
            ->subject('New Schedule Assigned')
            ->greeting("Hello {$facultyName},")
            ->line("A new schedule has been automatically assigned to you:")
            ->line("📘 Subject: {$subjectTitle}")
            ->line("👥 Section: {$sectionName}")
            ->line("🏫 Room: {$roomNo}")
            ->line("📅 Days: {$days}")
            ->line("⏰ Time: {$start} - {$end}")
            ->line("💻 Delivery Mode: {$deliveryMode}")
            ->line("📊 Status: Assigned")
            ->line('You may now check your faculty portal for details.');
    }
}

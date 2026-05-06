<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseEnrolled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Course $course
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Welcome to {$this->course->title}!")
            ->greeting("Hi {$notifiable->name},")
            ->line("Congratulations! You've successfully enrolled in **{$this->course->title}**.")
            ->line("You can now access all course materials and start learning at your own pace.")
            ->action('Start Learning', route('courses.show', $this->course->slug))
            ->line('Happy learning!')
            ->salutation('Best regards, The Online School Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'message' => "You have been enrolled in {$this->course->title}",
        ];
    }
}
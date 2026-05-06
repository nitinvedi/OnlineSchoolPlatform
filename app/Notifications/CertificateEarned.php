<?php

namespace App\Notifications;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateEarned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Certificate $certificate
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🎉 Congratulations! You've earned a certificate!")
            ->greeting("Hi {$notifiable->name},")
            ->line("Amazing work! You've successfully completed **{$this->certificate->course->title}** and earned your certificate.")
            ->line("Your dedication and hard work have paid off. This certificate represents your achievement and commitment to learning.")
            ->action('View Certificate', route('certificates.show', $this->certificate))
            ->action('Download PDF', route('certificates.download', $this->certificate))
            ->line('Keep up the great work and continue your learning journey!')
            ->salutation('Best regards, The Online School Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'certificate_id' => $this->certificate->id,
            'course_title' => $this->certificate->course->title,
            'message' => "You have earned a certificate for {$this->certificate->course->title}",
        ];
    }
}
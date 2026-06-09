<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $action;
    public User $user;
    public ?array $changes;
    public bool $passwordOnly;

    public function __construct(string $action, User $user, ?array $changes = null, bool $passwordOnly = false)
    {
        $this->action = $action;
        $this->user = $user;
        $this->changes = $changes;
        $this->passwordOnly = $passwordOnly;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->action) {
            'created' => 'New User Account Created: ' . $this->user->name,
            'updated' => 'User Profile Updated: ' . $this->user->name,
            'deleted' => 'User Account Deleted: ' . $this->user->name,
            default => 'User Notification: ' . $this->user->name,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

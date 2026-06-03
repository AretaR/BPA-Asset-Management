<?php

namespace App\Mail;

use App\Models\Asset;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $action;
    public Asset $asset;
    public ?string $dueDate;
    public ?string $notes;

    public function __construct(string $action, Asset $asset, ?string $dueDate = null, ?string $notes = null)
    {
        $this->action = $action;
        $this->asset = $asset;
        $this->dueDate = $dueDate;
        $this->notes = $notes;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->action) {
            'maintenance_due' => 'Maintenance Due: ' . $this->asset->name,
            'maintenance_overdue' => 'MAINTENANCE OVERDUE: ' . $this->asset->name,
            default => 'Maintenance Notification: ' . $this->asset->name,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

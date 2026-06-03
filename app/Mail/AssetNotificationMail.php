<?php

namespace App\Mail;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssetNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $action;
    public Asset $asset;
    public ?User $actor;
    public ?string $notes;

    public function __construct(string $action, Asset $asset, ?User $actor = null, ?string $notes = null)
    {
        $this->action = $action;
        $this->asset = $asset;
        $this->actor = $actor;
        $this->notes = $notes;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->action) {
            'created' => 'New Asset Added: ' . $this->asset->name,
            'updated' => 'Asset Updated: ' . $this->asset->name,
            'deleted' => 'Asset Removed: ' . $this->asset->name,
            'checked_out' => 'Asset Checked Out: ' . $this->asset->name,
            'checked_in' => 'Asset Checked In: ' . $this->asset->name,
            default => 'Asset Notification: ' . $this->asset->name,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.asset-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

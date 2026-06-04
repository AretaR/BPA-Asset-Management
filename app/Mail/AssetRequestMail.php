<?php

namespace App\Mail;

use App\Models\AssetRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssetRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public AssetRequest $assetRequest;
    public string $action;
    public ?User $actor;

    public function __construct(string $action, AssetRequest $assetRequest, ?User $actor = null)
    {
        $this->action = $action;
        $this->assetRequest = $assetRequest;
        $this->actor = $actor;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->action) {
            'submitted' => 'New Asset Request: ' . $this->assetRequest->asset_name,
            'approved' => 'Asset Request Approved: ' . $this->assetRequest->asset_name,
            'rejected' => 'Asset Request Rejected: ' . $this->assetRequest->asset_name,
            'issued' => 'Asset Issued: ' . $this->assetRequest->asset_name,
            'cancelled' => 'Asset Request Cancelled: ' . $this->assetRequest->asset_name,
            default => 'Asset Request Update: ' . $this->assetRequest->asset_name,
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.asset-request-notification');
    }

    public function attachments(): array
    {
        return [];
    }
}

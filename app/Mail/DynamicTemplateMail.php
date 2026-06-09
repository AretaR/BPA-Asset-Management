<?php

namespace App\Mail;

use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public NotificationTemplate $template;
    public string $renderedSubject;
    public string $renderedBody;

    public function __construct(NotificationTemplate $template, array $placeholders = [])
    {
        $this->template = $template;

        $this->renderedSubject = $this->replacePlaceholders($template->subject, $placeholders);
        $this->renderedBody = $this->replacePlaceholders($template->body, $placeholders);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->renderedSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dynamic-template',
            with: [
                'renderedBody' => $this->renderedBody,
                'subject' => $this->renderedSubject,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    protected function replacePlaceholders(string $text, array $placeholders): string
    {
        foreach ($placeholders as $key => $value) {
            $text = str_replace('{{' . $key . '}}', (string) $value, $text);
        }

        return $text;
    }
}

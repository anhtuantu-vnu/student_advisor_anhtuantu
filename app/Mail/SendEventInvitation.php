<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEventInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $event;
    public $fromName;
    public $fromTitle;
    public $toName;
    public $event_url;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $event, $fromName, $fromTitle, $toName, $event_url)
    {
        $this->subject = $subject;
        $this->event = $event;
        $this->fromName = $fromName;
        $this->fromTitle = $fromTitle;
        $this->toName = $toName;
        $this->event_url = $event_url;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mails.send_event_invitation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

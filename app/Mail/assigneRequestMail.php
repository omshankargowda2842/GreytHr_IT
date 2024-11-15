<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class assigneRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $employeeName;
    public $assignedBy;
    public $requestId;
    public $shortDescription;
    public $viewRequestUrl;
    public $superAdmin;
    public $category;

    /**
     * Create a new message instance.
     */
    public function __construct(
    $assigneName ,
    $requestId,
    $shortDescription,
    $category,)
    {
        $this->assignedBy =$assigneName;
        $this->requestId = $requestId;
        $this->shortDescription = $shortDescription;
        $this->category =$category;
    }

    public function build()
    {
      return $this->subject('Request Approved')
                    ->view('emails.assigne-request-mail');

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Assigne Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.assigne-request-mail',
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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class statusRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $employeeName;
    public $rejectionReason;
    public $pendingReason;
    public $requestId;
    public $shortDescription;
    public $viewRequestUrl;
    public $superAdmin;
    public $category;
    public $status;
    /**
     * Create a new message instance.
     */
    public function __construct( $employeeName,
    $requestId, $pendingReason,
    $shortDescription,
    $category,$status)
    {
        $this->employeeName = $employeeName;

        $this->requestId = $requestId;
        $this->pendingReason = $pendingReason;
        
        $this->shortDescription = $shortDescription;

        $this->category =$category;

        $this->status = $status;

    }

    public function build()
    {
      return $this->subject('Status Approved')
                    ->view('emails.status-request-mail');

    }



    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.status-request-mail',
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

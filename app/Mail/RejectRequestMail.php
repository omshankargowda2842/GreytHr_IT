<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectRequestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $employeeName;
    public $rejectionReason;
    public $requestId;
    public $shortDescription;
    public $viewRequestUrl;
    public $rejectedEmpName;

    /**
     * Create a new message instance.
     */
    public function __construct($employeeName, $rejectionReason, $requestId, $shortDescription,$RejetedEmployeeName)
    {
        $this->employeeName = $employeeName;
        $this->rejectionReason = $rejectionReason;
        $this->requestId = $requestId;
        $this->shortDescription = $shortDescription;
        $this->rejectedEmpName =$RejetedEmployeeName;


    }

    public function build()
    {
      return $this->subject('Request Rejected')
                    ->view('emails.reject-request-mail');

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reject Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reject-request-mail',
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

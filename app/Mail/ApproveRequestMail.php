<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproveRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $employeeName;
    public $rejectionReason;
    public $requestId;
    public $shortDescription;
    public $viewRequestUrl;
    public $superAdmin;
    public $category;

    /**
     * Create a new message instance.
     */
    public function __construct( $employeeName,
    $RejetedEmployeeName ,
    $requestId,
    $shortDescription,
    $category,)
    {
        $this->employeeName = $employeeName;
        $this->requestId = $requestId;

        $this->shortDescription = $shortDescription;
        $this->superAdmin =$RejetedEmployeeName;
        $this->category =$category;



    }

    public function build()
    {
      return $this->subject('Request Approved')
                    ->view('emails.approve-request-mail');

    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Approve Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.approve-request-mail',
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

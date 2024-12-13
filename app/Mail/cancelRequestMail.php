<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class cancelRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    public $employeeName;
    public $rejectionReason;
    public $requestId;
    public $shortDescription;
    public $viewRequestUrl;
    public $rejectedEmpName;
    public $category;

    /**
     * Create a new message instance.
     */
    public function __construct($cancelRequest,$employee,$rejectionReason,$Id)
{
   
    $this->employeeName = $cancelRequest->emp->first_name . ' ' . $cancelRequest->emp->last_name;
    $this->requestId = $Id;
    $this->rejectedEmpName = $employee->employee_name;
    $this->rejectionReason = $rejectionReason;
    $this->shortDescription =  $cancelRequest->description;

    $this->category = $cancelRequest->category;
}


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cancel Request Mail',
        );
    }


    public function build()
    {
      return $this->subject('Cancel Request')
                    ->view('emails.cancel-request-mail');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cancel-request-mail',
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

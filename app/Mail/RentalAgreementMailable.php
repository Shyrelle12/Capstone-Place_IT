<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentalAgreementMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $agreementData;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($agreementData, $pdfPath)
    {
        $this->agreementData = $agreementData;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Rental Agreement',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.rental_agreements',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    public function build()
    {
        return $this->subject('Your Rental Agreement Details')
                    ->view('emails.rental_agreements') // Optional: Add email body content
                    ->attach($this->pdfPath, [
                        'as' => 'RentalAgreement.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}

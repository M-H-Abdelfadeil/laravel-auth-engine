<?php

namespace App\Mail;

use App\Enums\VerificationPurposeEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $code;
    private $purpose;

    public function __construct($data)
    {
        $this->code = $data['code'];
        $this->purpose = $data['purpose'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {

        return new Envelope(
            subject: match ($this->purpose) {
                VerificationPurposeEnum::VERIFY->value => 'Account Activation Code',
                VerificationPurposeEnum::RESET_PASSWORD->value => 'Reset Password Code',
                VerificationPurposeEnum::CHANGE_EMAIL->value => 'Change Email Verification Code',
                VerificationPurposeEnum::CHANGE_PHONE->value => 'Change Phone Verification Code',
                default => config('app.name').' Notification',
            },
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

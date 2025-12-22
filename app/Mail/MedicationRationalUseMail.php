<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedicationRationalUseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $medication;
    public $clientName;
    public $clientEmail;
    public $teamName;
    public $teamPhone;
    public $teamEmail;
    public $teamAddress;
    public $dispenseRecordId;
    public $payload;


    /**
     * Create a new message instance.
     */
    public function __construct(
        array $medication,
        ?string $clientName = null,
        ?string $teamName = null,
        ?int $dispenseRecordId = null,
        ?array $payload = null,
        string $clientEmail,
        ?string $teamPhone = null,
        ?string $teamAddress = null,
        ?string $teamEmail = null
    ) {
        $this->payload = $payload;
        $this->medication = $medication;
        $this->clientName = $clientName;
        $this->teamName = $teamName;
        $this->dispenseRecordId = $dispenseRecordId;
        $this->clientEmail = $clientEmail;
        $this->teamPhone = $teamPhone;
        $this->teamAddress = $teamAddress;
        $this->teamEmail = $teamEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Uso Racional de Medicamentos - ' . $this->medication['name'],
            from: new Address('uso_racional_medicamentos@gestioncalidad.net.co', 'Promoción del Uso Racional de los Medicamentos'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.medication-info',
            with: [
                'medication' => $this->medication,
                'clientName' => $this->clientName,
                'teamName' => $this->teamName,
                'dispenseRecordId' => $this->dispenseRecordId,
                'payload' => $this->payload,
                'clientEmail' => $this->clientEmail,
                'teamPhone' => $this->teamPhone,
                'teamAddress' => $this->teamAddress,
                'teamEmail' => $this->teamEmail,
            ],
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

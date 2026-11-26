<?php

namespace App\Mail;

use App\Mail\Concerns\JointFacturePdf;
use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// E-mail de rappel envoyé au client qui n'a pas fini de régler sa facture
class RappelReglement extends Mailable
{
    use Queueable, SerializesModels, JointFacturePdf;

    public function __construct(public Facture $facture)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Rappel de règlement – Facture {$this->facture->numero_facture}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.rappel-reglement');
    }

    // La facture en PDF, jointe à l'e-mail
    public function attachments(): array
    {
        return $this->factureEnPieceJointe($this->facture);
    }
}

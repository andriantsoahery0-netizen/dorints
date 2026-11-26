<?php

namespace App\Mail;

use App\Mail\Concerns\JointFacturePdf;
use App\Models\Transfert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Reçu envoyé au client après chaque règlement de sa facture
class RecuReglement extends Mailable
{
    use Queueable, SerializesModels, JointFacturePdf;

    public function __construct(public Transfert $reglement)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Reçu de paiement – Facture {$this->reglement->commande->facture->numero_facture}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.recu-reglement');
    }

    // La facture à jour, avec ce règlement, en pièce jointe
    public function attachments(): array
    {
        return $this->factureEnPieceJointe($this->reglement->commande->facture);
    }
}

<?php

namespace App\Mail;

use App\Mail\Concerns\JointFacturePdf;
use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Envoyé au client quand sa commande est validée : récapitulatif + facture PDF
class CommandeValidee extends Mailable
{
    use Queueable, SerializesModels, JointFacturePdf;

    public function __construct(public Commande $commande)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Votre commande {$this->commande->numero_commande} est validée");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.commande-validee');
    }

    public function attachments(): array
    {
        return $this->factureEnPieceJointe($this->commande->facture);
    }
}

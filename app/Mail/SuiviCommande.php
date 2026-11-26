<?php

namespace App\Mail;

use App\Mail\Concerns\JointFacturePdf;
use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Envoyé au client quand sa commande passe « en cours » ou « livrée »
class SuiviCommande extends Mailable
{
    use Queueable, SerializesModels, JointFacturePdf;

    public function __construct(public Commande $commande)
    {
    }

    public function envelope(): Envelope
    {
        $etat = $this->commande->statut === 'livree' ? 'est livrée' : 'est en cours';

        return new Envelope(subject: "Votre commande {$this->commande->numero_commande} {$etat}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.suivi-commande');
    }

    // La facture à jour (montants réglés et reste à payer) en pièce jointe
    public function attachments(): array
    {
        return $this->commande->facture ? $this->factureEnPieceJointe($this->commande->facture) : [];
    }
}

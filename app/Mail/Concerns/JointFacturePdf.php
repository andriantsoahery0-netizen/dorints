<?php

namespace App\Mail\Concerns;

use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Attachment;

// Joint la facture en PDF à un e-mail (si la bibliothèque dompdf est installée)
trait JointFacturePdf
{
    protected function factureEnPieceJointe(Facture $facture): array
    {
        if (! class_exists(Pdf::class)) {
            return [];
        }

        return [
            Attachment::fromData(
                fn () => Pdf::loadView('factures.pdf', ['facture' => $facture])->output(),
                "Facture-{$facture->numero_facture}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}

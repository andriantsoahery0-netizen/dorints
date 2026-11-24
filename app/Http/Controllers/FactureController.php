<?php

namespace App\Http\Controllers;

use App\Models\Facture;

class FactureController extends Controller
{
    // Facture imprimable d'une commande validée
    public function show(Facture $facture)
    {
        $facture->load('commande.client', 'commande.lignes', 'commande.transferts', 'agent');

        return view('factures.show', compact('facture'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Transfert;
use App\Http\Controllers\RapportController;

class RapportController extends Controller
{
    public function index()
{
    $tauxActuel = \App\Models\TauxChange::actuel();

    $montantTotalMga = Transfert::where('devise_source', 'MGA')->sum('montant_envoye');
    $montantTotalEur = Transfert::where('devise_source', 'EUR')->sum('montant_envoye');

    // Total combiné, converti dans les deux devises
    if ($tauxActuel) {
        $totalGeneralEur = $montantTotalEur + ($montantTotalMga * $tauxActuel->taux_mga_vers_eur);
        $totalGeneralMga = $montantTotalMga + ($montantTotalEur * $tauxActuel->taux_eur_vers_mga);
    } else {
        $totalGeneralEur = null;
        $totalGeneralMga = null;
    }

    $stats = [
        'total_commandes' => Commande::count(),
        'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
        'commandes_en_cours' => Commande::where('statut', 'en_cours')->count(),
        'commandes_livrees' => Commande::where('statut', 'livree')->count(),
        'total_transferts' => Transfert::count(),
        'montant_total_mga' => $montantTotalMga,
        'montant_total_eur' => $montantTotalEur,
        'total_general_eur' => $totalGeneralEur,
        'total_general_mga' => $totalGeneralMga,
    ];

    $dernieresTransactions = Transfert::with(['client', 'commande'])
        ->latest()
        ->take(10)
        ->get();

    return view('rapports.index', compact('stats', 'dernieresTransactions'));
}
}
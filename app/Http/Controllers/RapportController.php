<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Facture;
use App\Models\Transfert;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index(Request $request)
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
            'commandes_validees' => Commande::where('statut', 'validee')->count(),
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

    // Totaux par mois pour l'année choisie
    $annees = Commande::pluck('created_at')->map(fn ($d) => $d->year)
        ->push(now()->year)->unique()->sortDesc()->values();
    $annee = (int) $request->input('annee', now()->year);
    $totauxParMois = $this->totauxParMois($annee);

    return view('rapports.index', compact('stats', 'dernieresTransactions', 'annees', 'annee', 'totauxParMois'));
}

// Pour chaque mois de l'année : nombre de commandes, montant facturé, montant réglé
private function totauxParMois(int $annee): array
{
    $parMois = fn ($requete) => $requete->whereYear('created_at', $annee)->get()
        ->groupBy(fn ($ligne) => $ligne->created_at->month);

    $commandes = $parMois(Commande::query());
    $factures = $parMois(Facture::query());
    $reglements = $parMois(Transfert::where('devise_source', 'MGA')->whereHas('commande.facture'));

    $noms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
        'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    $mois = [];
    foreach ($noms as $i => $nom) {
        $mois[] = [
            'mois' => $nom,
            'commandes' => isset($commandes[$i + 1]) ? $commandes[$i + 1]->count() : 0,
            'facture' => isset($factures[$i + 1]) ? $factures[$i + 1]->sum('montant') : 0,
            'regle' => isset($reglements[$i + 1]) ? $reglements[$i + 1]->sum('montant_envoye') : 0,
        ];
    }

    return $mois;
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Facture;
use App\Models\Transfert;
use Illuminate\Http\Request;
use App\Support\Csv;

class RapportController extends Controller
{
    public function index(Request $request)
{
    $tauxActuel = \App\Models\TauxChange::actuel();

    // Transferts des 3 derniers mois (mois en cours compris)
    $debutPeriode = now()->startOfMonth()->subMonths(2);
    $periode = $debutPeriode->translatedFormat('F') . ' – ' . now()->translatedFormat('F Y');
    $transfertsPeriode = fn () => Transfert::where('created_at', '>=', $debutPeriode);

    $montantTotalMga = $transfertsPeriode()->where('devise_source', 'MGA')->sum('montant_envoye');
    $montantTotalEur = $transfertsPeriode()->where('devise_source', 'EUR')->sum('montant_envoye');

    // Total général de la même période, converti dans les deux devises au taux actuel
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
        'total_transferts' => $transfertsPeriode()->count(),
        'montant_total_mga' => $montantTotalMga,
        'montant_total_eur' => $montantTotalEur,
        'total_general_eur' => $totalGeneralEur,
        'total_general_mga' => $totalGeneralMga,
        'periode' => $periode,
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

// Export Excel des totaux par mois de l'année choisie
public function export(Request $request)
{
    $annee = (int) $request->input('annee', now()->year);
    $mois = $this->totauxParMois($annee);

    $lignes = array_map(fn ($m) => [$m['mois'], $m['commandes'], Csv::nombre($m['facture']), Csv::nombre($m['regle'])], $mois);
    $lignes[] = ["Total {$annee}", array_sum(array_column($mois, 'commandes')),
        Csv::nombre(array_sum(array_column($mois, 'facture'))), Csv::nombre(array_sum(array_column($mois, 'regle')))];

    return Csv::telecharger("totaux-par-mois-{$annee}.csv", ['Mois', 'Commandes', 'Montant facturé (Ar)', 'Montant réglé (Ar)'], $lignes);
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
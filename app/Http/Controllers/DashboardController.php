<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\TauxChange;
use App\Models\Transfert;

class DashboardController extends Controller
{
    public function index()
    {
        $debutMois = now()->startOfMonth();

        // Règlements : transferts en ariary sur une commande facturée
        $reglements = fn () => Transfert::where('devise_source', 'MGA')->whereHas('commande.facture');

        $factureMois = (float) Facture::where('created_at', '>=', $debutMois)->sum('montant');
        $encaisseMois = (float) $reglements()->where('created_at', '>=', $debutMois)->sum('montant_envoye');
        $resteAEncaisser = max(0, (float) Facture::sum('montant') - (float) $reglements()->sum('montant_envoye'));

        $stats = [
            'facture_mois' => $factureMois,
            'encaisse_mois' => $encaisseMois,
            'pourcentage_encaisse' => $factureMois > 0 ? min(100, round($encaisseMois / $factureMois * 100)) : 0,
            'reste_a_encaisser' => $resteAEncaisser,
            'factures_ouvertes' => Facture::nonReglees()->count(),
            'clients' => Client::count(),
            'clients_a_jour' => Client::ayantRegle()->count(),
            'clients_a_relancer' => Client::nonRegle()->count(),
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
            'commandes_validees' => Commande::where('statut', 'validee')->count(),
            'commandes_en_cours' => Commande::where('statut', 'en_cours')->count(),
            'commandes_livrees' => Commande::where('statut', 'livree')->count(),
        ];

        // Facturé et encaissé sur les 6 derniers mois
        $mois = [];
        for ($i = 5; $i >= 0; $i--) {
            $debut = now()->startOfMonth()->subMonths($i);
            $fin = $debut->copy()->endOfMonth();
            $mois[] = [
                'libelle' => ucfirst($debut->locale('fr')->translatedFormat('M')),
                'facture' => (float) Facture::whereBetween('created_at', [$debut, $fin])->sum('montant'),
                'encaisse' => (float) $reglements()->whereBetween('created_at', [$debut, $fin])->sum('montant_envoye'),
            ];
        }
        $max = max(1, max(array_map(fn ($m) => max($m['facture'], $m['encaisse']), $mois)));
        foreach ($mois as &$m) {
            $m['h_facture'] = round($m['facture'] / $max * 100);
            $m['h_encaisse'] = round($m['encaisse'] / $max * 100);
        }
        unset($m);

        // Factures qui attendent un règlement, les plus anciennes d'abord
        $aRelancer = Facture::nonReglees()->with('commande.client')->oldest()->take(5)->get();

        // Commandes qui attendent la validation, et dernières commandes créées
        $aValider = Commande::with('client')->where('statut', 'en_attente')->oldest()->take(5)->get();
        $dernieres = Commande::with('client')->latest()->take(5)->get();

        $taux = TauxChange::actuel();

        return view('dashboard', compact('stats', 'mois', 'aRelancer', 'aValider', 'dernieres', 'taux'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\TauxChange;
use App\Models\Transfert;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Mail\RecuReglement;
use App\Support\EmailClient;

class TransfertController extends Controller
{
    // Formulaire de création, pour une commande donnée
    public function create(Commande $commande)
    {
        if ($refus = $this->verifierFacture($commande)) {
            return $refus;
        }

        $tauxActuel = TauxChange::actuel();

        if (!$tauxActuel) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', "Aucun taux de change n'est actif. Contactez l'administrateur.");
        }

        return view('transferts.create', compact('commande', 'tauxActuel'));
    }

    // Enregistrement du transfert
    public function store(Request $request, Commande $commande)
    {
        if ($refus = $this->verifierFacture($commande)) {
            return $refus;
        }

        $validated = $request->validate([
            'montant_envoye' => 'required|numeric|min:0.01',
            'sens' => 'required|in:mga_vers_eur,eur_vers_mga',
            'mode_paiement' => 'required|in:especes,mvola,orange_money,airtel_money,virement',
        ]);

        // Un règlement en ariary ne peut pas dépasser le reste à payer de la facture
        $reste = $commande->resteAPayer();
        if ($validated['sens'] === 'mga_vers_eur' && $validated['montant_envoye'] > $reste) {
            $resteAffiche = number_format($reste, 0, ',', ' ');

            return back()->withInput()->withErrors([
                'montant_envoye' => 'Le montant dépasse le reste à payer de la facture '
                    . "({$resteAffiche} Ar).",
            ]);
        }

        $tauxActuel = TauxChange::actuel();

        if (!$tauxActuel) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', "Aucun taux de change n'est actif.");
        }

        if ($validated['sens'] === 'mga_vers_eur') {
            $deviseSource = 'MGA';
            $deviseCible = 'EUR';
            $tauxApplique = $tauxActuel->taux_mga_vers_eur;
        } else {
            $deviseSource = 'EUR';
            $deviseCible = 'MGA';
            $tauxApplique = $tauxActuel->taux_eur_vers_mga;
        }

        $montantConverti = $validated['montant_envoye'] * $tauxApplique;

        $transfert = Transfert::create([
            'commande_id' => $commande->id,
            'client_id' => $commande->client_id,
            'montant_envoye' => $validated['montant_envoye'],
            'devise_source' => $deviseSource,
            'devise_cible' => $deviseCible,
            'taux_applique' => $tauxApplique,
            'montant_converti' => $montantConverti,
            'mode_paiement' => $validated['mode_paiement'],
            'user_id' => auth()->id(),
        ]);

        ActivityLog::enregistrer(
            'creation_transfert',
            "Transfert de {$validated['montant_envoye']} {$deviseSource} enregistré pour la commande {$commande->numero_commande}"
        );

        // Reçu envoyé au client pour un règlement en ariary
        $envoye = false;
        if ($deviseSource === 'MGA') {
            $envoye = EmailClient::envoyer($commande->client, new RecuReglement($transfert->load('commande.client', 'commande.facture')), 'reçu de règlement');
        }

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Règlement enregistré avec succès.' . EmailClient::mention($envoye));
    }

    // Le client règle sa facture : pas de règlement avant la validation de la commande
    private function verifierFacture(Commande $commande)
    {
        if (! $commande->facture()->exists()) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Validez d\'abord la commande : le règlement se fait sur la facture.');
        }

        if ($commande->resteAPayer() <= 0) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'La facture de cette commande est déjà entièrement réglée.');
        }

        return null;
    }
}
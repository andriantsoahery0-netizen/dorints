<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\TauxChange;
use App\Models\Transfert;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class TransfertController extends Controller
{
    // Formulaire de création, pour une commande donnée
    public function create(Commande $commande)
    {
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
        $validated = $request->validate([
            'montant_envoye' => 'required|numeric|min:0.01',
            'sens' => 'required|in:mga_vers_eur,eur_vers_mga',
        ]);

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

        Transfert::create([
            'commande_id' => $commande->id,
            'client_id' => $commande->client_id,
            'montant_envoye' => $validated['montant_envoye'],
            'devise_source' => $deviseSource,
            'devise_cible' => $deviseCible,
            'taux_applique' => $tauxApplique,
            'montant_converti' => $montantConverti,
            'user_id' => auth()->id(),
        ]);

        ActivityLog::enregistrer(
            'creation_transfert',
            "Transfert de {$validated['montant_envoye']} {$deviseSource} enregistré pour la commande {$commande->numero_commande}"
        );

return redirect()->route('commandes.show', $commande)
    ->with('success', 'Transfert enregistré avec succès.');
    }
}
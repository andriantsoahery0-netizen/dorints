<?php

namespace App\Http\Controllers;

use App\Models\TauxChange;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\TauxChangeModifie;

class TauxChangeController extends Controller
{
    // Affiche le taux actuel + historique
    public function index()
    {
        $tauxActuel = TauxChange::actuel();
        $historique = TauxChange::where('actif', false)
            ->latest()
            ->paginate(10);

        return view('taux-changes.index', compact('tauxActuel', 'historique'));
    }

    // Enregistre un nouveau taux (et désactive l'ancien)
    public function store(Request $request)
{
    $validated = $request->validate([
        'taux_eur_vers_mga' => 'required|numeric|min:0.0001',
    ]);

    // Désactive l'ancien taux actif (s'il existe)
    TauxChange::where('actif', true)->update(['actif' => false]);

    // Calcule automatiquement le taux inverse (MGA vers EUR)
    $tauxEurVersMga = $validated['taux_eur_vers_mga'];
    $tauxMgaVersEur = 1 / $tauxEurVersMga;

    // Crée le nouveau taux actif
    TauxChange::create([
    'taux_mga_vers_eur' => $tauxMgaVersEur,
    'taux_eur_vers_mga' => $tauxEurVersMga,
    'actif' => true,
    'user_id' => auth()->id(),
]);

    ActivityLog::enregistrer(
    'modification_taux_change',
    "Nouveau taux défini : 1 EUR = {$tauxEurVersMga} MGA"
);

// Notifie tous les agents du changement de taux
$agents = User::where('role', 'agent')->get();
foreach ($agents as $agent) {
    $agent->notify(new TauxChangeModifie($tauxEurVersMga, auth()->user()->name));
}

return redirect()->route('taux-changes.index')
    ->with('success', 'Taux de change mis à jour avec succès.');
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\Transfert;
use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\StatutCommandeChange;

class CommandeController extends Controller
{
    // Liste des commandes, avec filtrage par statut
    public function index(Request $request)
    {
        $query = Commande::with('client')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $commandes = $query->paginate(10)->withQueryString();

        return view('commandes.index', compact('commandes'));
    }

    // Formulaire de création
    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        return view('commandes.create', compact('clients'));
    }

    // Enregistrement d'une nouvelle commande
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string',
        ]);

        $commande = Commande::create([
            'numero_commande' => $this->genererNumeroCommande(),
            'client_id' => $validated['client_id'],
            'description' => $validated['description'],
            'statut' => 'en_attente',
            'user_id' => auth()->id(),
        ]);

ActivityLog::enregistrer(
    'creation_commande',
    "Commande {$commande->numero_commande} créée pour le client {$commande->client->nom} {$commande->client->prenom}"
);

return redirect()->route('commandes.index')
    ->with('success', 'Commande créée avec succès.');
    }

    // Détail d'une commande
    public function show(Commande $commande)
    {
        $commande->load('client', 'agent', 'transferts');
        return view('commandes.show', compact('commande'));
    }

    // Formulaire de modification (surtout pour changer le statut)
    public function edit(Commande $commande)
    {
        $clients = Client::orderBy('nom')->get();
        return view('commandes.edit', compact('commande', 'clients'));
    }

    // Mise à jour
    public function update(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'required|string',
            'statut' => 'required|in:en_attente,en_cours,livree',
        ]);

    $ancienStatut = $commande->statut;
    $commande->update($validated);

if ($ancienStatut !== $commande->statut) {
    ActivityLog::enregistrer(
        'modification_statut_commande',
        "Commande {$commande->numero_commande} : statut changé de '{$ancienStatut}' à '{$commande->statut}'"
    );

    // Notifie tous les admins du changement de statut
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new StatutCommandeChange($commande, $ancienStatut, $commande->statut));
    }
}

return redirect()->route('commandes.index')
    ->with('success', 'Commande mise à jour avec succès.');
    }

    // Suppression
    public function destroy(Commande $commande)
    {
        $commande->delete();

        return redirect()->route('commandes.index')
            ->with('success', 'Commande supprimée avec succès.');
    }

    // Génère un numéro de commande unique du type CMD-2026-0001
    private function genererNumeroCommande(): string
    {
        $annee = now()->year;

        $dernierNumero = Commande::where('numero_commande', 'like', "CMD-{$annee}-%")
            ->orderByDesc('numero_commande')
            ->value('numero_commande');

        if ($dernierNumero) {
            $dernierSequence = (int) substr($dernierNumero, -4);
            $nouvelleSequence = $dernierSequence + 1;
        } else {
            $nouvelleSequence = 1;
        }

        return sprintf('CMD-%d-%04d', $annee, $nouvelleSequence);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Support\Csv;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Liste des clients, avec leur situation de paiement
    public function index(Request $request)
    {
        $clients = $this->requete($request)->paginate(10)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    // Export Excel de la liste, avec les mêmes filtres que l'écran
    public function export(Request $request)
    {
        $situations = ['regle' => 'A tout réglé', 'non_regle' => 'Pas encore réglé', 'aucune_facture' => 'Aucune facture'];

        $lignes = $this->requete($request)->get()->map(fn (Client $c) => [
            $c->nom, $c->prenom, $c->telephone, $c->adresse, $c->email,
            Csv::nombre($c->totalFacture()), Csv::nombre($c->totalRegle()), Csv::nombre($c->resteARegler()),
            $situations[$c->situation()],
        ]);

        return Csv::telecharger('clients-' . now()->format('Y-m-d') . '.csv',
            ['Nom', 'Prénom', 'Téléphone', 'Adresse', 'Email', 'Total facturé (Ar)', 'Total réglé (Ar)', 'Reste à régler (Ar)', 'Situation'],
            $lignes);
    }

    // Requête commune à la liste et à l'export : filtres « situation » et « recherche »
    private function requete(Request $request)
    {
        $query = Client::avecSituation()->latest();

        if ($request->input('situation') === 'regle') {
            $query->ayantRegle();
        } elseif ($request->input('situation') === 'non_regle') {
            $query->nonRegle();
        }

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where(function ($q) use ($recherche) {
                $q->where(fn ($n) => $n->nomContient($recherche))
                    ->orWhere('telephone', 'like', "%{$recherche}%")
                    ->orWhere(fn ($t) => $t->telephoneContient($recherche))
                    ->orWhere('email', 'like', "%{$recherche}%");
            });
        }

        return $query;
    }

    // Formulaire de création
    public function create()
    {
        return view('clients.create');
    }

    // Enregistrement d'un nouveau client
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => ['required', 'string', 'max:20', function ($attribut, $valeur, $echec) {
                if (! Client::telephoneValide($valeur)) {
                    $echec("Le numéro de téléphone n'est pas valide. Format attendu : 03X XX XXX XX ou +261 3X XX XXX XX.");
                }
            }],
            'adresse' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès.');
    }

    // Détail d'un client (avec son historique)
    public function show(Client $client)
    {
        $client->load(['commandes.facture', 'transferts']);
        return view('clients.show', compact('client'));
    }

    // Formulaire de modification
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    // Mise à jour
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => ['required', 'string', 'max:20', function ($attribut, $valeur, $echec) {
                if (! Client::telephoneValide($valeur)) {
                    $echec("Le numéro de téléphone n'est pas valide. Format attendu : 03X XX XXX XX ou +261 3X XX XXX XX.");
                }
            }],
            'adresse' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    // Suppression
    public function destroy(Client $client)
    {
        // Un client qui a des commandes est conservé, pour garder l'historique et les factures
        $nbCommandes = $client->commandes()->count();
        if ($nbCommandes > 0) {
            return redirect()->route('clients.index')
                ->with('error', "Impossible de supprimer {$client->prenom} {$client->nom} : ce client a {$nbCommandes} commande(s) enregistrée(s).");
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
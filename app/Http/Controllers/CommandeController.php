<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\Transfert;
use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\StatutCommandeChange;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;

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

    // Enregistrement d'une nouvelle commande avec ses produits
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'description' => 'nullable|string',
        ] + $this->reglesLignes(), $this->messagesLignes());

        $commande = DB::transaction(function () use ($validated) {
            $commande = Commande::create([
                'numero_commande' => $this->genererNumeroCommande(),
                'client_id' => $validated['client_id'],
                'description' => $validated['description'] ?? null,
                'montant_total' => 0,
                'statut' => 'en_attente',
                'user_id' => auth()->id(),
            ]);
            $this->enregistrerLignes($commande, $validated['lignes']);

            return $commande;
        });

        ActivityLog::enregistrer(
            'creation_commande',
            "Commande {$commande->numero_commande} créée pour le client {$commande->client->nom} {$commande->client->prenom}"
        );

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande créée avec succès.');
    }

    // Détail d'une commande
    public function show(Commande $commande)
    {
        $commande->load('client', 'agent', 'lignes', 'transferts', 'facture');
        return view('commandes.show', compact('commande'));
    }

    // Formulaire de modification (surtout pour changer le statut)
    public function edit(Commande $commande)
    {
        $clients = Client::orderBy('nom')->get();
        $commande->load('lignes', 'facture');
        return view('commandes.edit', compact('commande', 'clients'));
    }

    // Mise à jour
    public function update(Request $request, Commande $commande)
    {
        $facturee = $commande->facture()->exists();

        // Une commande facturée garde ses produits et ses prix : seul le statut change
        $regles = [
            'statut' => 'required|in:en_attente,validee,en_cours,livree',
        ];
        if (! $facturee) {
            $regles += [
                'client_id' => 'required|exists:clients,id',
                'description' => 'nullable|string',
            ] + $this->reglesLignes();
        }
        $validated = $request->validate($regles, $this->messagesLignes());

        // Seule la validation (bouton « Valider la commande ») fait sortir une commande de « en attente »
        if (! $facturee && $commande->statut === 'en_attente' && $validated['statut'] !== 'en_attente') {
            return back()->withInput()->withErrors([
                'statut' => 'La commande doit d\'abord être validée avec le bouton « Valider la commande ».',
            ]);
        }

        // Une commande facturée ne revient pas en attente
        if ($facturee && $validated['statut'] === 'en_attente') {
            return back()->withInput()->withErrors([
                'statut' => 'Une commande validée et facturée ne peut pas revenir en attente.',
            ]);
        }

        $ancienStatut = $commande->statut;

        DB::transaction(function () use ($commande, $validated, $facturee) {
            if ($facturee) {
                $commande->update(['statut' => $validated['statut']]);
            } else {
                $commande->update([
                    'client_id' => $validated['client_id'],
                    'description' => $validated['description'] ?? null,
                    'statut' => $validated['statut'],
                ]);
                $commande->lignes()->delete();
                $this->enregistrerLignes($commande, $validated['lignes']);
            }
        });

        if ($ancienStatut !== $commande->statut) {
            ActivityLog::enregistrer(
                'modification_statut_commande',
                "Commande {$commande->numero_commande} : statut changé de '{$ancienStatut}' à '{$commande->statut}'"
            );
            $this->notifierAdmins($commande, $ancienStatut);
        }

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande mise à jour avec succès.');
    }

    // Validation d'une commande : elle passe à « validee » et une facture est générée
    public function valider(Commande $commande)
    {
        if ($commande->statut !== 'en_attente' || $commande->facture()->exists()) {
            return back()->with('error', 'Cette commande a déjà été validée.');
        }

        if (! $commande->lignes()->exists() || $commande->montant_total <= 0) {
            return back()->with('error', 'Ajoutez les produits commandés et leur prix avant de valider la commande.');
        }

        $facture = DB::transaction(function () use ($commande) {
            $commande->update(['statut' => 'validee']);

            return Facture::create([
                'numero_facture' => Facture::genererNumero(),
                'commande_id' => $commande->id,
                'montant' => $commande->montant_total,
                'user_id' => auth()->id(),
            ]);
        });

        ActivityLog::enregistrer(
            'validation_commande',
            "Commande {$commande->numero_commande} validée, facture {$facture->numero_facture} émise"
        );
        $this->notifierAdmins($commande, 'en_attente');

        return redirect()->route('commandes.show', $commande)
            ->with('success', "Commande validée. Facture {$facture->numero_facture} générée.");
    }

    // Règles de validation des produits commandés
    private function reglesLignes(): array
    {
        return [
            'lignes' => 'required|array|min:1',
            'lignes.*.designation' => 'required|string|max:255',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
        ];
    }

    private function messagesLignes(): array
    {
        return [
            'lignes.required' => 'Ajoutez au moins un produit.',
            'lignes.*.designation.required' => 'Indiquez le nom de chaque produit.',
            'lignes.*.quantite.required' => 'Indiquez la quantité de chaque produit.',
            'lignes.*.quantite.integer' => 'La quantité doit être un nombre entier.',
            'lignes.*.quantite.min' => 'La quantité doit être au moins 1.',
            'lignes.*.prix_unitaire.required' => 'Indiquez le prix unitaire de chaque produit.',
            'lignes.*.prix_unitaire.numeric' => 'Le prix unitaire doit être un nombre.',
            'lignes.*.prix_unitaire.min' => 'Le prix unitaire ne peut pas être négatif.',
        ];
    }

    // Enregistre les produits et recalcule le montant total de la commande
    private function enregistrerLignes(Commande $commande, array $lignes): void
    {
        $total = 0;
        foreach ($lignes as $ligne) {
            $commande->lignes()->create([
                'designation' => $ligne['designation'],
                'quantite' => $ligne['quantite'],
                'prix_unitaire' => $ligne['prix_unitaire'],
            ]);
            $total += $ligne['quantite'] * $ligne['prix_unitaire'];
        }

        $commande->update(['montant_total' => $total]);
    }

    // Notifie tous les administrateurs d'un changement de statut
    private function notifierAdmins(Commande $commande, string $ancienStatut): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new StatutCommandeChange($commande, $ancienStatut, $commande->statut));
        }
    }

    // Suppression
    public function destroy(Commande $commande)
    {
        // Une commande facturée est conservée pour garder la trace de sa facture
        if ($commande->facture()->exists()) {
            return redirect()->route('commandes.index')
                ->with('error', "La commande {$commande->numero_commande} est facturée et ne peut pas être supprimée.");
        }

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
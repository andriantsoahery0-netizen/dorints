<?php

namespace App\Http\Controllers;

use App\Mail\RappelReglement;
use App\Models\ActivityLog;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Support\Csv;

class FactureController extends Controller
{
    // Liste des factures, filtrable par état de règlement
    public function index(Request $request)
    {
        $factures = $this->requete($request)->paginate(10)->withQueryString();

        return view('factures.index', compact('factures'));
    }

    // Export Excel des factures, avec le même filtre que l'écran
    public function export(Request $request)
    {
        $etats = ['paye' => 'Réglée', 'partiel' => 'Partielle', 'non_paye' => 'Non réglée'];

        $lignes = $this->requete($request)->get()->map(fn (Facture $f) => [
            $f->numero_facture,
            $f->created_at->format('d/m/Y'),
            $f->commande->client->nom . ' ' . $f->commande->client->prenom,
            $f->commande->numero_commande,
            Csv::nombre($f->montant),
            Csv::nombre($f->commande->montantPaye()),
            Csv::nombre($f->commande->resteAPayer()),
            $etats[$f->commande->statutPaiement()],
            $f->derniere_relance_at?->format('d/m/Y H:i'),
        ]);

        return Csv::telecharger('factures-' . now()->format('Y-m-d') . '.csv',
            ['N° facture', 'Date', 'Client', 'Commande', 'Montant (Ar)', 'Réglé (Ar)', 'Reste (Ar)', 'État', 'Dernière relance'],
            $lignes);
    }

    // Requête commune à la liste et à l'export : filtre « etat »
    private function requete(Request $request)
    {
        $query = Facture::with('commande.client')->latest();

        if ($request->input('etat') === 'non_reglees') {
            $query->nonReglees();
        } elseif ($request->input('etat') === 'reglees') {
            $query->reglees();
        }

        // Recherche par numéro de facture, numéro de commande, nom ou prénom du client
        if ($request->filled('recherche')) {
            $recherche = trim($request->recherche);
            $query->where(function ($q) use ($recherche) {
                $q->where('numero_facture', 'like', "%{$recherche}%")
                    ->orWhereHas('commande', fn ($c) => $c->where('numero_commande', 'like', "%{$recherche}%"))
                    ->orWhereHas('commande.client', fn ($c) => $c->nomContient($recherche));
            });
        }

        return $query;
    }

    // Facture imprimable d'une commande validée
    public function show(Facture $facture)
    {
        $facture->load('commande.client', 'commande.lignes', 'commande.transferts', 'agent');

        return view('factures.show', compact('facture'));
    }

    // Téléchargement de la facture en PDF
    public function pdf(Facture $facture)
    {
        if (! class_exists(Pdf::class)) {
            return back()->with('error', 'Le PDF nécessite la bibliothèque dompdf : composer require barryvdh/laravel-dompdf');
        }

        $facture->load('commande.client', 'commande.lignes', 'commande.transferts', 'agent');

        return Pdf::loadView('factures.pdf', compact('facture'))
            ->download("Facture-{$facture->numero_facture}.pdf");
    }

    // Relance par e-mail : envoie le rappel de règlement au client
    public function relancerEmail(Facture $facture)
    {
        $facture->load('commande.client');
        $client = $facture->commande->client;

        if ($facture->commande->resteAPayer() <= 0) {
            return back()->with('error', "La facture {$facture->numero_facture} est déjà réglée.");
        }
        if (! $client->email) {
            return back()->with('error',
                "{$client->prenom} {$client->nom} n'a pas d'adresse e-mail. "
                . 'Relancez-le par WhatsApp.');
        }

        try {
            Mail::to($client->email)->send(new RappelReglement($facture));
        } catch (\Throwable $e) {
            return back()->with('error',
                "L'e-mail n'a pas pu être envoyé. "
                . 'Vérifiez la configuration MAIL du fichier .env.');
        }

        $this->noterRelance($facture, "par e-mail à {$client->email}");

        return back()->with('success',
            "Rappel envoyé par e-mail à {$client->prenom} {$client->nom}.");
    }

    // Relance par WhatsApp : ouvre WhatsApp avec le message déjà écrit
    public function relancerWhatsapp(Facture $facture)
    {
        $facture->load('commande.client');
        $numero = $facture->numeroWhatsapp();

        if (! $numero) {
            return back()->with('error', "Ce client n'a pas de numéro de téléphone.");
        }

        $this->noterRelance($facture, 'par WhatsApp');

        return redirect()->away('https://wa.me/' . $numero . '?text=' . rawurlencode($facture->messageRappel()));
    }

    // Garde la date de relance et l'inscrit au journal
    private function noterRelance(Facture $facture, string $moyen): void
    {
        $facture->update(['derniere_relance_at' => now()]);

        ActivityLog::enregistrer(
            'relance_facture',
            "Relance {$moyen} pour la facture {$facture->numero_facture}"
        );
    }
}

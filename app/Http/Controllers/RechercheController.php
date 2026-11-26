<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    // Recherche globale : clients, commandes et factures en une fois
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $clients = $commandes = $factures = collect();

        if ($q !== '') {
            $clients = Client::where('nom', 'like', "%{$q}%")
                ->orWhere('prenom', 'like', "%{$q}%")
                ->orWhere('telephone', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orderBy('nom')->take(10)->get();

            $commandes = Commande::with('client')
                ->where('numero_commande', 'like', "%{$q}%")
                ->orWhereHas('client', fn ($c) => $c->where('nom', 'like', "%{$q}%")->orWhere('prenom', 'like', "%{$q}%"))
                ->orWhereHas('lignes', fn ($l) => $l->where('designation', 'like', "%{$q}%"))
                ->latest()->take(10)->get();

            $factures = Facture::with('commande.client')
                ->where('numero_facture', 'like', "%{$q}%")
                ->orWhereHas('commande.client', fn ($c) => $c->where('nom', 'like', "%{$q}%")->orWhere('prenom', 'like', "%{$q}%"))
                ->latest()->take(10)->get();
        }

        return view('recherche', compact('q', 'clients', 'commandes', 'factures'));
    }
}

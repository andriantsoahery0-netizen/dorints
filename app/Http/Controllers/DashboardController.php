<?php

namespace App\Http\Controllers;

use App\Models\Commande;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
            'commandes_en_cours' => Commande::where('statut', 'en_cours')->count(),
            'commandes_livrees' => Commande::where('statut', 'livree')->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
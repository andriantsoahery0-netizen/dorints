@php
    $commande = $facture->commande;
    $client = $commande->client;
    $sp = $commande->statutPaiement();
    $reglements = $commande->transferts->where('devise_source', 'MGA')->sortBy('created_at');
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facture {{ $facture->numero_facture }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .feuille { box-shadow: none !important; border: none !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800">

    <div class="no-print max-w-3xl mx-auto pt-6 flex justify-end gap-2">
        <a href="{{ route('commandes.show', $commande) }}"
           class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
            Retour à la commande
        </a>
        @if ($commande->resteAPayer() > 0)
            <a href="{{ route('transferts.create', $commande) }}"
               class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                Enregistrer un règlement
            </a>
        @endif
        <a href="{{ route('factures.pdf', $facture) }}"
           class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
            Télécharger le PDF
        </a>
        <button type="button" onclick="window.print()"
                class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
            Imprimer
        </button>
    </div>

    @if (session('error'))
        <div class="no-print max-w-3xl mx-auto mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="feuille max-w-3xl mx-auto my-6 bg-white shadow-sm border border-slate-200 rounded-xl p-10">

        {{-- En-tête --}}
        <div class="flex justify-between items-start border-b border-slate-200 pb-6">
            <div>
                <p class="text-2xl font-bold text-blue-900">D'ORINTS</p>
                <p class="text-sm text-slate-500">Gestion des commandes et transferts</p>
                <p class="text-sm text-slate-500">Antananarivo, Madagascar</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-slate-800 uppercase tracking-wide">Facture</p>
                <p class="text-sm text-slate-600 mt-1">N° <span class="font-semibold">{{ $facture->numero_facture }}</span></p>
                <p class="text-sm text-slate-600">Date : {{ $facture->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Client et commande --}}
        <div class="grid grid-cols-2 gap-6 py-6 border-b border-slate-200">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Facturé à</p>
                <p class="font-semibold">{{ $client->nom }} {{ $client->prenom }}</p>
                <p class="text-sm text-slate-600">{{ $client->adresse }}</p>
                <p class="text-sm text-slate-600">{{ $client->telephone }}</p>
                @if ($client->email)
                    <p class="text-sm text-slate-600">{{ $client->email }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Commande</p>
                <p class="font-semibold">{{ $commande->numero_commande }}</p>
                <p class="text-sm text-slate-600">Passée le {{ $commande->created_at->format('d/m/Y') }}</p>
                <p class="text-sm text-slate-600">Validée par {{ $facture->agent->name }}</p>
            </div>
        </div>

        {{-- Produits commandés --}}
        <table class="w-full text-sm mt-6">
            <thead>
                <tr class="border-b-2 border-slate-200 text-left text-xs text-slate-500 uppercase">
                    <th class="py-2">Désignation</th>
                    <th class="py-2 text-right">Quantité</th>
                    <th class="py-2 text-right">Prix unitaire</th>
                    <th class="py-2 text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($commande->lignes as $ligne)
                    <tr class="border-b border-slate-100">
                        <td class="py-3 pr-4">{{ $ligne->designation }}</td>
                        <td class="py-3 text-right">{{ $ligne->quantite }}</td>
                        <td class="py-3 text-right whitespace-nowrap">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} Ar</td>
                        <td class="py-3 text-right whitespace-nowrap">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} Ar</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totaux --}}
        <div class="flex justify-end mt-6">
            <div class="w-72 text-sm space-y-2">
                <div class="flex justify-between font-semibold text-base border-b border-slate-200 pb-2">
                    <span>Total à payer</span>
                    <span>{{ number_format($facture->montant, 0, ',', ' ') }} Ar</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Déjà payé</span>
                    <span>{{ number_format($commande->montantPaye(), 0, ',', ' ') }} Ar</span>
                </div>
                <div class="flex justify-between font-semibold">
                    <span>Reste à payer</span>
                    <span>{{ number_format($commande->resteAPayer(), 0, ',', ' ') }} Ar</span>
                </div>
                <div class="text-right pt-1">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $sp === 'paye' ? 'bg-green-100 text-green-800' : ($sp === 'partiel' ? 'bg-red-50 text-red-700' : 'bg-red-100 text-red-800') }}">
                        {{ ['paye' => 'Réglée', 'partiel' => 'Partiellement réglée', 'non_paye' => 'Non réglée'][$sp] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Règlements reçus --}}
        <div class="mt-8">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Règlements reçus</p>
            @if ($reglements->isEmpty())
                <p class="text-sm text-slate-500">Aucun règlement reçu pour cette facture.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs text-slate-500 uppercase">
                            <th class="py-2">Date</th>
                            <th class="py-2">Mode de paiement</th>
                            <th class="py-2 text-right">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reglements as $reglement)
                            <tr class="border-b border-slate-100">
                                <td class="py-2">{{ $reglement->created_at->format('d/m/Y') }}</td>
                                <td class="py-2">{{ \App\Models\Transfert::MODES[$reglement->mode_paiement] ?? '—' }}</td>
                                <td class="py-2 text-right whitespace-nowrap">{{ number_format($reglement->montant_envoye, 0, ',', ' ') }} Ar</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <p class="text-xs text-slate-400 mt-10 text-center">
            Modes de paiement acceptés : espèces, MVola, Orange Money, Airtel Money, virement bancaire.
        </p>
    </div>
</body>
</html>

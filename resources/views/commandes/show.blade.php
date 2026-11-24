<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
                Commande {{ $commande->numero_commande }}
            </h2>
            <div class="flex items-center gap-2">
                @if ($commande->statut === 'en_attente')
                    <form method="POST" action="{{ route('commandes.valider', $commande) }}">
                        @csrf
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Valider la commande
                        </button>
                    </form>
                @endif
                @if ($commande->facture)
                    <a href="{{ route('factures.show', $commande->facture) }}"
                       class="bg-indigo-700 hover:bg-indigo-800 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                        Voir la facture
                    </a>
                @endif
                <a href="{{ route('commandes.edit', $commande) }}"
                   class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                    Modifier
                </a>
                <a href="{{ route('commandes.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Informations générales --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Informations</h3>
                    <span @class([
                        'px-3 py-1 rounded-full text-sm font-medium',
                        'bg-yellow-100 text-yellow-800' => $commande->statut === 'en_attente',
                        'bg-purple-100 text-purple-800' => $commande->statut === 'validee',
                        'bg-blue-100 text-blue-800' => $commande->statut === 'en_cours',
                        'bg-green-100 text-green-800' => $commande->statut === 'livree',
                    ])>
                        {{ str_replace('_', ' ', $commande->statut) }}
                    </span>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Client</dt>
                        <dd class="text-gray-900 font-medium">
                            <a href="{{ route('clients.show', $commande->client) }}" class="text-indigo-700 hover:underline">
                                {{ $commande->client->nom }} {{ $commande->client->prenom }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Agent créateur</dt>
                        <dd class="text-gray-900 font-medium">{{ $commande->agent->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Date de création</dt>
                        <dd class="text-gray-900 font-medium">{{ $commande->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @if ($commande->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm text-gray-500">Remarque</dt>
                            <dd class="text-gray-900">{{ $commande->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Produits commandés --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Produits commandés</h3>
                @if ($commande->lignes->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun produit. Ajoutez-les avec le bouton « Modifier ».</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($commande->lignes as $ligne)
                                <tr>
                                    <td class="px-4 py-3 text-gray-800">{{ $ligne->designation }}</td>
                                    <td class="px-4 py-3 text-right text-gray-800">{{ $ligne->quantite }}</td>
                                    <td class="px-4 py-3 text-right text-gray-800 whitespace-nowrap">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} Ar</td>
                                    <td class="px-4 py-3 text-right text-gray-800 font-medium whitespace-nowrap">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} Ar</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200">
                                <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-gray-900 whitespace-nowrap">{{ number_format($commande->montant_total, 0, ',', ' ') }} Ar</td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>

            {{-- Suivi du paiement --}}
            @php $sp = $commande->statutPaiement(); @endphp
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Paiement du client</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $sp === 'paye' ? 'bg-green-100 text-green-800' : ($sp === 'partiel' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                        {{ ['paye' => 'Payé', 'partiel' => 'Paiement partiel', 'non_paye' => 'Non payé'][$sp] }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><p class="text-sm text-gray-500">Montant total</p>
                         <p class="text-lg font-semibold text-gray-800">{{ number_format($commande->montant_total, 0, ',', ' ') }} Ar</p></div>
                    <div><p class="text-sm text-gray-500">Payé</p>
                         <p class="text-lg font-semibold text-gray-800">{{ number_format($commande->montantPaye(), 0, ',', ' ') }} Ar</p></div>
                    <div><p class="text-sm text-gray-500">Reste à payer</p>
                         <p class="text-lg font-semibold text-gray-800">{{ number_format($commande->resteAPayer(), 0, ',', ' ') }} Ar</p></div>
                </div>
            </div>

            {{-- Transferts liés --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Règlements et transferts</h3>
                    @if ($commande->facture && $commande->resteAPayer() > 0)
                        <a href="{{ route('transferts.create', $commande) }}"
                           class="flex items-center gap-1.5 bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Enregistrer un règlement
                        </a>
                    @endif
                </div>
                @unless ($commande->facture)
                    <p class="mb-3 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                        Validez la commande pour émettre la facture : les règlements se font ensuite sur cette facture.
                    </p>
                @endunless
                @if ($commande->transferts->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun règlement enregistré pour cette commande.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant envoyé</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant converti</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mode</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($commande->transferts as $transfert)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ number_format($transfert->montant_envoye, 2) }} {{ $transfert->devise_source }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ number_format($transfert->montant_converti, 2) }} {{ $transfert->devise_cible }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ \App\Models\Transfert::MODES[$transfert->mode_paiement] ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $transfert->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

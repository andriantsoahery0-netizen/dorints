<x-app-layout>
    <x-slot name="header">
        <div class="max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Commande {{ $commande->numero_commande }}
                </h2>
                <div class="flex items-center gap-2">
                    @if ($commande->statut === 'en_attente')
                        <form method="POST" action="{{ route('commandes.valider', $commande) }}">
                            @csrf
                            <button type="submit"
                                    class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                                Valider la commande
                            </button>
                        </form>
                    @endif
                    @if ($commande->facture)
                        <a href="{{ route('factures.show', $commande->facture) }}"
                           class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Voir la facture
                        </a>
                    @endif
                    <a href="{{ route('commandes.edit', $commande) }}"
                       class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                        Modifier
                    </a>
                    <a href="{{ route('commandes.index') }}"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                        Retour
                    </a>
                </div>
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">

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
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-display text-lg font-bold text-slate-900">Informations</h3>
                    <x-statut :statut="$commande->statut" />
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-slate-500">Client</dt>
                        <dd class="text-slate-900 font-medium">
                            <a href="{{ route('clients.show', $commande->client) }}" class="text-blue-700 hover:underline">
                                {{ $commande->client->nom }} {{ $commande->client->prenom }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Agent créateur</dt>
                        <dd class="text-slate-900 font-medium">{{ $commande->agent->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Date de création</dt>
                        <dd class="text-slate-900 font-medium">{{ $commande->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    @if ($commande->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm text-slate-500">Remarque</dt>
                            <dd class="text-slate-900">{{ $commande->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Produits commandés --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Produits commandés</h3>
                @if ($commande->lignes->isEmpty())
                    <p class="text-slate-500 text-sm">Aucun produit. Ajoutez-les avec le bouton « Modifier ».</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[620px] table-fixed divide-y divide-slate-100">
                            <colgroup><col><col class="w-[6.5rem]"><col class="w-[9.5rem]"><col class="w-[9.5rem]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Produit</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Quantité</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Prix unitaire</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($commande->lignes as $ligne)
                                    <tr>
                                        <td class="px-3 py-3 text-slate-800 text-left break-words">{{ $ligne->designation }}</td>
                                        <td class="px-3 py-3 text-slate-800 text-right whitespace-nowrap">{{ $ligne->quantite }}</td>
                                        <td class="px-3 py-3 text-slate-800 text-right whitespace-nowrap">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} Ar</td>
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} Ar</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-slate-200">
                                    <td colspan="3" class="px-3 py-3 text-right font-semibold text-slate-700">Total</td>
                                    <td class="px-3 py-3 font-bold text-slate-900 text-right whitespace-nowrap">{{ number_format($commande->montant_total, 0, ',', ' ') }} Ar</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Suivi du paiement --}}
            @php $sp = $commande->statutPaiement(); @endphp
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-display text-lg font-bold text-slate-900">Paiement du client</h3>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $sp === 'paye' ? 'bg-green-100 text-green-800' : ($sp === 'partiel' ? 'bg-red-50 text-red-700' : 'bg-red-100 text-red-800') }}">
                        {{ ['paye' => 'Payé', 'partiel' => 'Paiement partiel', 'non_paye' => 'Non payé'][$sp] }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><p class="text-sm text-slate-500">Montant total</p>
                         <p class="text-lg font-semibold text-slate-800">{{ number_format($commande->montant_total, 0, ',', ' ') }} Ar</p></div>
                    <div><p class="text-sm text-slate-500">Payé</p>
                         <p class="text-lg font-semibold text-slate-800">{{ number_format($commande->montantPaye(), 0, ',', ' ') }} Ar</p></div>
                    <div><p class="text-sm text-slate-500">Reste à payer</p>
                         <p class="text-lg font-semibold text-slate-800">{{ number_format($commande->resteAPayer(), 0, ',', ' ') }} Ar</p></div>
                </div>
            </div>

            {{-- Transferts liés --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-display text-lg font-bold text-slate-900">Règlements et transferts</h3>
                    @if ($commande->facture && $commande->resteAPayer() > 0)
                        <a href="{{ route('transferts.create', $commande) }}"
                           class="flex items-center gap-1.5 bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Enregistrer un règlement
                        </a>
                    @endif
                </div>
                @unless ($commande->facture)
                    <p class="mb-3 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
                        Validez la commande pour émettre la facture : les règlements se font ensuite sur cette facture.
                    </p>
                @endunless
                @if ($commande->transferts->isEmpty())
                    <p class="text-slate-500 text-sm">Aucun règlement enregistré pour cette commande.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[560px] table-fixed divide-y divide-slate-100">
                            <colgroup><col class="w-[28%]"><col class="w-[28%]"><col class="w-[22%]"><col class="w-[22%]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant envoyé</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant converti</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Mode</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($commande->transferts as $transfert)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ number_format($transfert->montant_envoye, 2, ',', ' ') }} {{ $transfert->devise_source }}</td>
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ number_format($transfert->montant_converti, 2, ',', ' ') }} {{ $transfert->devise_cible }}</td>
                                        <td class="px-3 py-3 text-slate-600 text-center whitespace-nowrap">{{ \App\Models\Transfert::MODES[$transfert->mode_paiement] ?? '—' }}</td>
                                        <td class="px-3 py-3 text-slate-500 text-center whitespace-nowrap">{{ $transfert->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

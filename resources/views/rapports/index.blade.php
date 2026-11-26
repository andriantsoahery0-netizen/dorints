<x-app-layout>
    <x-slot name="header">
        <div class="max-w-6xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Rapports et Statistiques
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Cartes de statistiques commandes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-6">
                    <p class="text-sm text-slate-500">Total commandes</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_commandes'] }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <p class="text-sm text-slate-600">En attente</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['commandes_en_attente'] }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <p class="text-sm text-slate-600">Validées</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['commandes_validees'] }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <p class="text-sm text-slate-600">En cours</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['commandes_en_cours'] }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <p class="text-sm text-slate-600">Livrées</p>
                    <p class="text-3xl font-bold text-slate-900 mt-1">{{ $stats['commandes_livrees'] }}</p>
                </div>
            </div>

            {{-- Cartes de statistiques transferts : 3 derniers mois --}}
            <p class="-mb-2 text-sm font-semibold text-slate-700">Transferts des 3 derniers mois <span class="font-normal text-slate-500">({{ $stats['periode'] }})</span></p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-xl border border-slate-100 p-6">
                    <p class="text-sm text-slate-500">Total transferts</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total_transferts'] }}</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <p class="text-sm text-slate-600">Total envoyé en MGA</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['montant_total_mga'], 0, ',', ' ') }} Ar</p>
                </div>
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <p class="text-sm text-slate-600">Total envoyé en EUR</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ number_format($stats['montant_total_eur'], 2, ',', ' ') }} €</p>
                </div>
            </div>

            {{-- Total général des 3 derniers mois, converti au taux actuel --}}
            @if ($stats['total_general_eur'] !== null)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-blue-50 border border-blue-100 shadow-sm rounded-xl p-6">
                        <p class="text-sm text-blue-900">Total général des 3 derniers mois, en euros</p>
                        <p class="mt-1 font-display text-2xl font-bold text-blue-900">{{ number_format($stats['total_general_eur'], 2, ',', ' ') }} €</p>
                        <p class="mt-1 text-xs text-blue-800/80">{{ ucfirst($stats['periode']) }}, converti au taux actuel</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 shadow-sm rounded-xl p-6">
                        <p class="text-sm text-blue-900">Total général des 3 derniers mois, en ariary</p>
                        <p class="mt-1 font-display text-2xl font-bold text-blue-900">{{ number_format($stats['total_general_mga'], 0, ',', ' ') }} Ar</p>
                        <p class="mt-1 text-xs text-blue-800/80">{{ ucfirst($stats['periode']) }}, converti au taux actuel</p>
                    </div>
                </div>
            @endif

            {{-- Totaux par mois --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-display text-lg font-bold text-slate-900">Totaux par mois</h3>
                    <div class="flex items-center gap-2">
                    <a href="{{ route('rapports.export', ['annee' => $annee]) }}"
                       class="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" /></svg>
                        Exporter (Excel)
                    </a>
                    <form method="GET" action="{{ route('rapports.index') }}">
                        <select name="annee" onchange="this.form.submit()"
                                class="rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-600 focus:ring-blue-600">
                            @foreach ($annees as $a)
                                <option value="{{ $a }}" @selected($a == $annee)>{{ $a }}</option>
                            @endforeach
                        </select>
                    </form>
                    </div>
                </div>
                @php
                    $totalAnnee = [
                        'commandes' => array_sum(array_column($totauxParMois, 'commandes')),
                        'facture' => array_sum(array_column($totauxParMois, 'facture')),
                        'regle' => array_sum(array_column($totauxParMois, 'regle')),
                    ];
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] table-fixed divide-y divide-slate-100">
                        <colgroup><col class="w-[31%]"><col class="w-[19%]"><col class="w-[25%]"><col class="w-[25%]"></colgroup>
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Mois</th>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Commandes</th>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant facturé</th>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant réglé</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($totauxParMois as $ligne)
                                <tr @class(['text-slate-400' => $ligne['commandes'] == 0 && $ligne['facture'] == 0 && $ligne['regle'] == 0, 'text-slate-800' => ! ($ligne['commandes'] == 0 && $ligne['facture'] == 0 && $ligne['regle'] == 0)])>
                                    <td class="px-3 py-2 text-left whitespace-nowrap">{{ $ligne['mois'] }}</td>
                                    <td class="px-3 py-2 text-right whitespace-nowrap">{{ $ligne['commandes'] }}</td>
                                    <td class="px-3 py-2 text-right whitespace-nowrap">{{ number_format($ligne['facture'], 0, ',', ' ') }} Ar</td>
                                    <td class="px-3 py-2 text-right whitespace-nowrap">{{ number_format($ligne['regle'], 0, ',', ' ') }} Ar</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200 font-semibold text-slate-900">
                                <td class="px-3 py-3 text-left whitespace-nowrap">Total {{ $annee }}</td>
                                <td class="px-3 py-3 text-right whitespace-nowrap">{{ $totalAnnee['commandes'] }}</td>
                                <td class="px-3 py-3 text-right whitespace-nowrap">{{ number_format($totalAnnee['facture'], 0, ',', ' ') }} Ar</td>
                                <td class="px-3 py-3 text-right whitespace-nowrap">{{ number_format($totalAnnee['regle'], 0, ',', ' ') }} Ar</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Dernières transactions --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Dernières transactions</h3>

                @if ($dernieresTransactions->isEmpty())
                    <p class="text-slate-500 text-sm">Aucune transaction pour le moment.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[820px] table-fixed divide-y divide-slate-100">
                            <colgroup><col class="w-[10.5rem]"><col><col class="w-[10.5rem]"><col class="w-[9.5rem]"><col class="w-[10.5rem]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Commande</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Client</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant envoyé</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant converti</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($dernieresTransactions as $transfert)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-3 py-3 text-left whitespace-nowrap">
                                            <a href="{{ route('commandes.show', $transfert->commande) }}" class="text-blue-700 hover:underline font-medium">
                                                {{ $transfert->commande->numero_commande }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-3 text-slate-600 text-left break-words">{{ $transfert->client->nom }} {{ $transfert->client->prenom }}</td>
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ number_format($transfert->montant_envoye, 2, ',', ' ') }} {{ $transfert->devise_source }}</td>
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ number_format($transfert->montant_converti, 2, ',', ' ') }} {{ $transfert->devise_cible }}</td>
                                        <td class="px-3 py-3 text-slate-500 text-center whitespace-nowrap">{{ $transfert->created_at->format('d/m/Y à H:i') }}</td>
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

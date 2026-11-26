<x-app-layout>
    <x-slot name="header">
        <div class="max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Fiche client — {{ $client->nom }} {{ $client->prenom }}
                </h2>
                <div class="space-x-2">
                    <a href="{{ route('clients.edit', $client) }}"
                       class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium py-2 px-4 rounded-lg text-sm">
                        Modifier
                    </a>
                    <a href="{{ route('clients.index') }}"
                       class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-medium py-2 px-4 rounded-lg text-sm">
                        Retour
                    </a>
                </div>
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Informations générales --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Informations</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-slate-500">Nom complet</dt>
                        <dd class="text-slate-900">{{ $client->nom }} {{ $client->prenom }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Téléphone</dt>
                        <dd class="text-slate-900">{{ $client->telephone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Email</dt>
                        <dd class="text-slate-900">{{ $client->email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-slate-500">Adresse</dt>
                        <dd class="text-slate-900">{{ $client->adresse }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Situation de paiement --}}
            @php $sit = $client->situation(); @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-display text-lg font-bold text-slate-900">Situation de paiement</h3>
                    <span @class([
                        'px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-green-100 text-green-800' => $sit === 'regle',
                        'bg-red-100 text-red-800' => $sit === 'non_regle',
                        'bg-slate-100 text-slate-600' => $sit === 'aucune_facture',
                    ])>
                        {{ ['regle' => 'A tout réglé', 'non_regle' => 'Pas encore réglé', 'aucune_facture' => 'Aucune facture'][$sit] }}
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><p class="text-sm text-slate-500">Total facturé</p>
                         <p class="text-lg font-semibold text-slate-800">{{ number_format($client->totalFacture(), 0, ',', ' ') }} Ar</p></div>
                    <div><p class="text-sm text-slate-500">Réglé</p>
                         <p class="text-lg font-semibold text-slate-800">{{ number_format($client->totalRegle(), 0, ',', ' ') }} Ar</p></div>
                    <div><p class="text-sm text-slate-500">Reste à régler</p>
                         <p class="text-lg font-semibold {{ $client->resteARegler() > 0 ? 'text-red-700' : 'text-slate-800' }}">{{ number_format($client->resteARegler(), 0, ',', ' ') }} Ar</p></div>
                </div>
            </div>

            {{-- Historique des commandes --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Historique des commandes</h3>
                @if ($client->commandes->isEmpty())
                    <p class="text-slate-500">Aucune commande enregistrée.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[820px] table-fixed divide-y divide-slate-200">
                            <colgroup><col><col class="w-[8rem]"><col><col class="w-[9.5rem]"><col class="w-[9.5rem]"><col class="w-[7.5rem]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-left">N°</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-center">Statut</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-left">Facture</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-right">Montant</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-right">Reste</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($client->commandes as $commande)
                                    <tr>
                                        <td class="px-3 py-3 text-left whitespace-nowrap">
                                            <a href="{{ route('commandes.show', $commande) }}" class="text-blue-700 hover:underline">{{ $commande->numero_commande }}</a>
                                        </td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            <x-statut :statut="$commande->statut" />
                                        </td>
                                        <td class="px-3 py-3 text-left whitespace-nowrap">
                                            @if ($commande->facture)
                                                <a href="{{ route('factures.show', $commande->facture) }}" class="text-blue-700 hover:underline">{{ $commande->facture->numero_facture }}</a>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-right whitespace-nowrap">{{ number_format($commande->montant_total, 0, ',', ' ') }} Ar</td>
                                        <td class="px-3 py-3 text-right whitespace-nowrap">{{ $commande->facture ? number_format($commande->resteAPayer(), 0, ',', ' ') . ' Ar' : '—' }}</td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">{{ $commande->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Historique des transferts --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Historique des transferts d'argent</h3>
                @if ($client->transferts->isEmpty())
                    <p class="text-slate-500">Aucun transfert enregistré.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[560px] table-fixed divide-y divide-slate-200">
                            <colgroup><col class="w-[28%]"><col class="w-[28%]"><col class="w-[22%]"><col class="w-[22%]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-right">Montant envoyé</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-right">Montant converti</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-right">Taux appliqué</th>
                                    <th class="px-3 py-2 text-xs font-medium text-slate-500 uppercase text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($client->transferts as $transfert)
                                    <tr>
                                        <td class="px-3 py-3 text-right whitespace-nowrap">{{ number_format($transfert->montant_envoye, 2, ',', ' ') }} {{ $transfert->devise_source }}</td>
                                        <td class="px-3 py-3 text-right whitespace-nowrap">{{ number_format($transfert->montant_converti, 2, ',', ' ') }} {{ $transfert->devise_cible }}</td>
                                        <td class="px-3 py-3 text-right whitespace-nowrap">{{ $transfert->taux_applique }}</td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">{{ $transfert->created_at->format('d/m/Y') }}</td>
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
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">Factures</h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Recherche par numéro de facture, de commande ou par nom du client --}}
            <form action="{{ route('factures.index') }}" method="GET" class="mb-4 flex gap-2">
                @if (request('etat'))
                    <input type="hidden" name="etat" value="{{ request('etat') }}">
                @endif
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="recherche" value="{{ request('recherche') }}"
                           placeholder="Rechercher par numéro de facture, de commande ou nom du client..."
                           class="w-full pl-10 rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                </div>
                <button type="submit"
                        class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                    Rechercher
                </button>
                @if (request('recherche'))
                    <a href="{{ route('factures.index', array_filter(['etat' => request('etat')])) }}"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                        Réinitialiser
                    </a>
                @endif
            </form>

            @php
                $onglets = ['' => 'Toutes', 'non_reglees' => 'Non réglées', 'reglees' => 'Réglées'];
            @endphp
            <div class="mb-4 flex flex-wrap items-center gap-2">
                @foreach ($onglets as $cle => $libelle)
                    <a href="{{ route('factures.index', array_filter(['etat' => $cle, 'recherche' => request('recherche')])) }}"
                       @class([
                           'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                           'bg-blue-800 text-white' => request('etat', '') === $cle,
                           'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => request('etat', '') !== $cle,
                       ])>{{ $libelle }}</a>
                @endforeach
                <div class="flex-1"></div>
                <a href="{{ route('factures.export', request()->query()) }}"
               class="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" /></svg>
                Exporter (Excel)
            </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">
                <div class="p-6 overflow-x-auto">
                    @if ($factures->isEmpty())
                        <p class="py-10 text-center text-slate-500">Aucune facture.</p>
                    @else
                        <table class="w-full min-w-[880px] table-fixed divide-y divide-slate-100 text-sm">
                            <colgroup><col class="w-[9.5rem]"><col><col class="w-[8.5rem]"><col class="w-[8.5rem]"><col class="w-[7.5rem]"><col class="w-[7rem]"><col class="w-[7.5rem]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-2 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Facture</th>
                                    <th class="px-2 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Client</th>
                                    <th class="px-2 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant</th>
                                    <th class="px-2 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Reste</th>
                                    <th class="px-2 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">État</th>
                                    <th class="px-2 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                    <th class="px-2 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($factures as $facture)
                                    @php $sp = $facture->commande->statutPaiement(); $reste = $facture->commande->resteAPayer(); @endphp
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <span class="block font-medium text-slate-800">{{ $facture->numero_facture }}</span>
                                            <a href="{{ route('commandes.show', $facture->commande) }}" class="text-sm text-blue-700 hover:underline">{{ $facture->commande->numero_commande }}</a>
                                        </td>
                                        <td class="px-2 py-3"><x-nom-client :client="$facture->commande->client" /></td>
                                        <td class="px-2 py-3 text-right whitespace-nowrap">{{ number_format($facture->montant, 0, ',', ' ') }} Ar</td>
                                        <td class="px-2 py-3 text-right whitespace-nowrap font-medium {{ $reste > 0 ? 'text-red-700' : 'text-slate-800' }}">{{ number_format($reste, 0, ',', ' ') }} Ar</td>
                                        <td class="px-2 py-3 text-center">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $sp === 'paye' ? 'bg-green-100 text-green-800' : ($sp === 'partiel' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ['paye' => 'Réglée', 'partiel' => 'Partielle', 'non_paye' => 'Non réglée'][$sp] }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-3 text-center text-slate-500 whitespace-nowrap">{{ $facture->created_at->format('d/m/Y') }}</td>
                                        <td class="px-2 py-3 text-right space-x-3 whitespace-nowrap">
                                            <a href="{{ route('factures.show', $facture) }}" class="text-blue-700 hover:text-blue-900 font-medium text-sm">Voir</a>
                                            @if ($reste > 0)
                                                <a href="{{ route('transferts.create', $facture->commande) }}" class="text-green-700 hover:text-green-900 font-medium text-sm">Régler</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">{{ $factures->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

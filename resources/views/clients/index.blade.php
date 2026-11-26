<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Gestion des Clients
                </h2>
                <div class="flex items-center gap-2">
                <a href="{{ route('clients.export', request()->query()) }}"
                   class="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" /></svg>
                    Exporter (Excel)
                </a>
                <a href="{{ route('clients.create') }}"
                   class="flex items-center gap-1.5 bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau client
                </a>
                </div>
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $onglets = ['' => 'Tous', 'regle' => 'Ont tout réglé', 'non_regle' => 'Pas encore réglé'];
            @endphp
            <div class="mb-4 flex flex-wrap gap-2">
                @foreach ($onglets as $cle => $libelle)
                    <a href="{{ route('clients.index', array_filter(['situation' => $cle, 'recherche' => request('recherche')])) }}"
                       @class([
                           'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                           'bg-blue-800 text-white' => request('situation', '') === $cle,
                           'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => request('situation', '') !== $cle,
                       ])>
                        {{ $libelle }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('clients.index') }}" method="GET" class="mb-4 flex gap-2">
                @if (request('situation'))
                    <input type="hidden" name="situation" value="{{ request('situation') }}">
                @endif
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="recherche" value="{{ request('recherche') }}"
                           placeholder="Rechercher par nom, prénom, téléphone, email..."
                           class="w-full pl-10 rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                </div>
                <button type="submit"
                        class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                    Rechercher
                </button>
                @if (request('recherche'))
                    <a href="{{ route('clients.index') }}"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                        Réinitialiser
                    </a>
                @endif
            </form>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100">
                <div class="p-6">

                    @if ($clients->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <p class="text-slate-500">Aucun client trouvé.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] table-fixed divide-y divide-slate-100">
                                <colgroup><col><col><col class="w-[8.5rem]"><col class="w-[8rem]"><col class="w-[8.5rem]"><col class="w-[9rem]"><col class="w-[13rem]"></colgroup>
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Nom</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Prénom</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Téléphone</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Facturé</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Reste à régler</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Situation</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach ($clients as $client)
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="px-3 py-3 font-medium text-slate-800 text-left break-words hyphens-auto">{{ $client->nom }}</td>
                                            <td class="px-3 py-3 text-slate-600 text-left break-words hyphens-auto">{{ $client->prenom }}</td>
                                            <td class="px-3 py-3 text-slate-600 text-left whitespace-nowrap">{{ $client->telephone }}</td>
                                            <td class="px-3 py-3 text-slate-800 text-right whitespace-nowrap">{{ number_format($client->totalFacture(), 0, ',', ' ') }} Ar</td>
                                            <td class="px-3 py-3 font-medium {{ $client->resteARegler() > 0 ? 'text-red-700' : 'text-slate-800' }} text-right whitespace-nowrap">{{ number_format($client->resteARegler(), 0, ',', ' ') }} Ar</td>
                                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                                @php $sit = $client->situation(); @endphp
                                                <span @class([
                                                    'px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap',
                                                    'bg-green-100 text-green-800' => $sit === 'regle',
                                                    'bg-red-100 text-red-800' => $sit === 'non_regle',
                                                    'bg-slate-100 text-slate-600' => $sit === 'aucune_facture',
                                                ])>
                                                    {{ ['regle' => 'A tout réglé', 'non_regle' => 'Pas encore réglé', 'aucune_facture' => 'Aucune facture'][$sit] }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-right whitespace-nowrap">
                                                <x-actions-ligne :voir="route('clients.show', $client)" :modifier="route('clients.edit', $client)"
                                                                 :supprimer="route('clients.destroy', $client)" confirmation="Supprimer ce client ?" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $clients->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

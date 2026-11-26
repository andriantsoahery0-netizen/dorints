<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Gestion des Commandes
                </h2>
                <div class="flex items-center gap-2">
                <a href="{{ route('commandes.export', request()->query()) }}"
                   class="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" /></svg>
                    Exporter (Excel)
                </a>
                <a href="{{ route('commandes.create') }}"
                   class="flex items-center gap-1.5 bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle commande
                </a>
                </div>
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('commandes.index') }}" method="GET" class="mb-4 flex gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="recherche" value="{{ request('recherche') }}"
                           placeholder="Rechercher par numéro de commande ou nom du client..."
                           class="w-full pl-10 rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                </div>
                <button type="submit"
                        class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                    Rechercher
                </button>
                @if (request('recherche'))
                    <a href="{{ route('commandes.index') }}"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                        Réinitialiser
                    </a>
                @endif
            </form>

            {{-- Filtres par statut --}}
            <div class="mb-4 flex gap-2 flex-wrap">
                <a href="{{ route('commandes.index') }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-blue-800 text-white' => !request('statut'),
                       'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' => request('statut'),
                   ])>
                    Toutes
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_attente']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-blue-800 text-white' => request('statut') === 'en_attente',
                       'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => request('statut') !== 'en_attente',
                   ])>
                    En attente
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'validee']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-blue-800 text-white' => request('statut') === 'validee',
                       'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => request('statut') !== 'validee',
                   ])>
                    Validées
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_cours']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-blue-800 text-white' => request('statut') === 'en_cours',
                       'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => request('statut') !== 'en_cours',
                   ])>
                    En cours
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'livree']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-blue-800 text-white' => request('statut') === 'livree',
                       'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => request('statut') !== 'livree',
                   ])>
                    Livrées
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100">
                <div class="p-6">

                    @if ($commandes->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-slate-500">Aucune commande trouvée.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] table-fixed divide-y divide-slate-100">
                                <colgroup><col class="w-[9.5rem]"><col><col class="w-[8.5rem]"><col class="w-[7.5rem]"><col class="w-[7rem]"><col class="w-[13rem]"></colgroup>
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">N° commande</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Client</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Statut</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach ($commandes as $commande)
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="px-3 py-3 font-medium text-slate-800 text-left whitespace-nowrap">{{ $commande->numero_commande }}</td>
                                            <td class="px-3 py-3 text-left"><x-nom-client :client="$commande->client" /></td>
                                            <td class="px-3 py-3 text-slate-800 text-right whitespace-nowrap">{{ number_format($commande->montant_total, 0, ',', ' ') }} Ar</td>
                                            <td class="px-3 py-3 text-center whitespace-nowrap">
                                                <x-statut :statut="$commande->statut" />
                                            </td>
                                            <td class="px-3 py-3 text-slate-500 text-center whitespace-nowrap">{{ $commande->created_at->format('d/m/Y') }}</td>
                                            <td class="px-3 py-3 text-right whitespace-nowrap">
                                                <x-actions-ligne :voir="route('commandes.show', $commande)" :modifier="route('commandes.edit', $commande)"
                                                                 :supprimer="route('commandes.destroy', $commande)" confirmation="Supprimer cette commande ?" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $commandes->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

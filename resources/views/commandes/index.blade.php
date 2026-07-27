<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Commandes
            </h2>
            <a href="{{ route('commandes.create') }}"
               class="flex items-center gap-1.5 bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle commande
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('commandes.index') }}" method="GET" class="mb-4 flex gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="recherche" value="{{ request('recherche') }}"
                           placeholder="Rechercher par numéro de commande ou nom du client..."
                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                </div>
                <button type="submit"
                        class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                    Rechercher
                </button>
                @if (request('recherche'))
                    <a href="{{ route('commandes.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                        Réinitialiser
                    </a>
                @endif
            </form>

            {{-- Filtres par statut --}}
            <div class="mb-4 flex gap-2 flex-wrap">
                <a href="{{ route('commandes.index') }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-indigo-800 text-white' => !request('statut'),
                       'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' => request('statut'),
                   ])>
                    Toutes
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_attente']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-yellow-500 text-white' => request('statut') === 'en_attente',
                       'bg-yellow-50 text-yellow-800 border border-yellow-200 hover:bg-yellow-100' => request('statut') !== 'en_attente',
                   ])>
                    En attente
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_cours']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-blue-500 text-white' => request('statut') === 'en_cours',
                       'bg-blue-50 text-blue-800 border border-blue-200 hover:bg-blue-100' => request('statut') !== 'en_cours',
                   ])>
                    En cours
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'livree']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium transition',
                       'bg-green-500 text-white' => request('statut') === 'livree',
                       'bg-green-50 text-green-800 border border-green-200 hover:bg-green-100' => request('statut') !== 'livree',
                   ])>
                    Livrées
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6">

                    @if ($commandes->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500">Aucune commande trouvée.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">N° commande</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($commandes as $commande)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $commande->numero_commande }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $commande->client->nom }} {{ $commande->client->prenom }}</td>
                                        <td class="px-4 py-3">
                                            <span @class([
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-yellow-100 text-yellow-800' => $commande->statut === 'en_attente',
                                                'bg-blue-100 text-blue-800' => $commande->statut === 'en_cours',
                                                'bg-green-100 text-green-800' => $commande->statut === 'livree',
                                            ])>
                                                {{ str_replace('_', ' ', $commande->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500">{{ $commande->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-right space-x-3">
                                            <a href="{{ route('commandes.show', $commande) }}"
                                               class="text-indigo-700 hover:text-indigo-900 font-medium text-sm">Voir</a>
                                            <a href="{{ route('commandes.edit', $commande) }}"
                                               class="text-amber-600 hover:text-amber-800 font-medium text-sm">Modifier</a>
                                            <form action="{{ route('commandes.destroy', $commande) }}" method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Supprimer cette commande ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-6">
                            {{ $commandes->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

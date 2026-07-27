<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Commandes
            </h2>
            <a href="{{ route('commandes.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                + Nouvelle commande
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('commandes.index') }}" method="GET" class="mb-4 flex gap-2">
                <input type="text" name="recherche" value="{{ request('recherche') }}"
                       placeholder="Rechercher par numéro de commande ou nom du client..."
                       class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                    Rechercher
                </button>
                @if (request('recherche'))
                    <a href="{{ route('commandes.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm">
                        Réinitialiser
                    </a>
                @endif
            </form>

            {{-- Filtres par statut --}}
            <div class="mb-4 flex gap-2">
                <a href="{{ route('commandes.index') }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium',
                       'bg-gray-800 text-white' => !request('statut'),
                       'bg-gray-200 text-gray-700' => request('statut'),
                   ])>
                    Toutes
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_attente']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium',
                       'bg-yellow-500 text-white' => request('statut') === 'en_attente',
                       'bg-yellow-100 text-yellow-800' => request('statut') !== 'en_attente',
                   ])>
                    En attente
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_cours']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium',
                       'bg-blue-500 text-white' => request('statut') === 'en_cours',
                       'bg-blue-100 text-blue-800' => request('statut') !== 'en_cours',
                   ])>
                    En cours
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'livree']) }}"
                   @class([
                       'px-3 py-1.5 rounded-lg text-sm font-medium',
                       'bg-green-500 text-white' => request('statut') === 'livree',
                       'bg-green-100 text-green-800' => request('statut') !== 'livree',
                   ])>
                    Livrées
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($commandes->isEmpty())
                        <p class="text-gray-500 text-center py-8">Aucune commande trouvée.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">N° commande</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($commandes as $commande)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $commande->numero_commande }}</td>
                                        <td class="px-4 py-3">{{ $commande->client->nom }} {{ $commande->client->prenom }}</td>
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
                                        <td class="px-4 py-3">{{ $commande->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <a href="{{ route('commandes.show', $commande) }}"
                                               class="text-blue-600 hover:underline text-sm">Voir</a>
                                            <a href="{{ route('commandes.edit', $commande) }}"
                                               class="text-yellow-600 hover:underline text-sm">Modifier</a>
                                            <form action="{{ route('commandes.destroy', $commande) }}" method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Supprimer cette commande ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm">
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
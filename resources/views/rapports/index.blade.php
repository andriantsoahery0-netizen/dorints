<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rapports et Statistiques
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cartes de statistiques commandes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total commandes</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_commandes'] }}</p>
                </div>
                <div class="bg-yellow-50 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-yellow-700">En attente</p>
                    <p class="text-3xl font-bold text-yellow-800">{{ $stats['commandes_en_attente'] }}</p>
                </div>
                <div class="bg-blue-50 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-blue-700">En cours</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $stats['commandes_en_cours'] }}</p>
                </div>
                <div class="bg-green-50 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-green-700">Livrées</p>
                    <p class="text-3xl font-bold text-green-800">{{ $stats['commandes_livrees'] }}</p>
                </div>
            </div>

            {{-- Cartes de statistiques transferts --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total transferts</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_transferts'] }}</p>
                </div>
                <div class="bg-purple-50 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-purple-700">Total envoyé en MGA</p>
                    <p class="text-2xl font-bold text-purple-800">{{ number_format($stats['montant_total_mga'], 0, ',', ' ') }} Ar</p>
                </div>
                    <div class="bg-indigo-50 shadow-sm rounded-lg p-6">
                        <p class="text-sm text-indigo-700">Total envoyé en EUR</p>
                        <p class="text-2xl font-bold text-indigo-800">{{ number_format($stats['montant_total_eur'], 2, ',', ' ') }} €</p>
                    </div>
                </div>

            {{-- Total général combiné (converti) --}}
            @if ($stats['total_general_eur'] !== null)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-800 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-300">Total général (en MGA)</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total_general_mga'], 0, ',', ' ') }} Ar</p>
                </div>
                <div class="bg-gray-800 shadow-sm rounded-lg p-6">
                    <p class="text-sm text-gray-300">Total général (en EUR)</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total_general_eur'], 2, ',', ' ') }} €</p>
                </div>
            </div>
            @endif

            {{-- Dernières transactions --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Dernières transactions</h3>

                @if ($dernieresTransactions->isEmpty())
                    <p class="text-gray-500">Aucune transaction pour le moment.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Commande</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant envoyé</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant converti</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($dernieresTransactions as $transfert)
                                <tr>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('commandes.show', $transfert->commande) }}" class="text-blue-600 hover:underline">
                                            {{ $transfert->commande->numero_commande }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $transfert->client->nom }} {{ $transfert->client->prenom }}</td>
                                    <td class="px-4 py-3">{{ number_format($transfert->montant_envoye, 2) }} {{ $transfert->devise_source }}</td>
                                    <td class="px-4 py-3">{{ number_format($transfert->montant_converti, 2) }} {{ $transfert->devise_cible }}</td>
                                    <td class="px-4 py-3">{{ $transfert->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
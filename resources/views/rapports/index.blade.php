<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
            Rapports et Statistiques
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Cartes de statistiques commandes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                    <p class="text-sm text-gray-500">Total commandes</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_commandes'] }}</p>
                </div>
                <div class="bg-yellow-50 shadow-sm rounded-xl border border-yellow-100 p-6">
                    <p class="text-sm text-yellow-700">En attente</p>
                    <p class="text-3xl font-bold text-yellow-800 mt-1">{{ $stats['commandes_en_attente'] }}</p>
                </div>
                <div class="bg-blue-50 shadow-sm rounded-xl border border-blue-100 p-6">
                    <p class="text-sm text-blue-700">En cours</p>
                    <p class="text-3xl font-bold text-blue-800 mt-1">{{ $stats['commandes_en_cours'] }}</p>
                </div>
                <div class="bg-green-50 shadow-sm rounded-xl border border-green-100 p-6">
                    <p class="text-sm text-green-700">Livrées</p>
                    <p class="text-3xl font-bold text-green-800 mt-1">{{ $stats['commandes_livrees'] }}</p>
                </div>
            </div>

            {{-- Cartes de statistiques transferts --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                    <p class="text-sm text-gray-500">Total transferts</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_transferts'] }}</p>
                </div>
                <div class="bg-indigo-50 shadow-sm rounded-xl border border-indigo-100 p-6">
                    <p class="text-sm text-indigo-700">Total envoyé en MGA</p>
                    <p class="text-2xl font-bold text-indigo-900 mt-1">{{ number_format($stats['montant_total_mga'], 0, ',', ' ') }} Ar</p>
                </div>
                <div class="bg-amber-50 shadow-sm rounded-xl border border-amber-100 p-6">
                    <p class="text-sm text-amber-700">Total envoyé en EUR</p>
                    <p class="text-2xl font-bold text-amber-900 mt-1">{{ number_format($stats['montant_total_eur'], 2, ',', ' ') }} €</p>
                </div>
            </div>

            {{-- Total général combiné --}}
            @if ($stats['total_general_eur'] !== null)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-800 shadow-lg rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-400/10 rounded-full -mr-8 -mt-8"></div>
                        <p class="text-sm text-indigo-300 relative">Total général (tout confondu, converti)</p>
                        <p class="text-2xl font-bold text-white mt-1 relative">{{ number_format($stats['total_general_eur'], 2, ',', ' ') }} €</p>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-800 shadow-lg rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-400/10 rounded-full -mr-8 -mt-8"></div>
                        <p class="text-sm text-indigo-300 relative">Total général (tout confondu, converti)</p>
                        <p class="text-2xl font-bold text-white mt-1 relative">{{ number_format($stats['total_general_mga'], 0, ',', ' ') }} Ar</p>
                    </div>
                </div>
            @endif

            {{-- Dernières transactions --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Dernières transactions</h3>

                @if ($dernieresTransactions->isEmpty())
                    <p class="text-gray-500 text-sm">Aucune transaction pour le moment.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Commande</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant envoyé</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant converti</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($dernieresTransactions as $transfert)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('commandes.show', $transfert->commande) }}" class="text-indigo-700 hover:underline font-medium">
                                            {{ $transfert->commande->numero_commande }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $transfert->client->nom }} {{ $transfert->client->prenom }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ number_format($transfert->montant_envoye, 2) }} {{ $transfert->devise_source }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ number_format($transfert->montant_converti, 2) }} {{ $transfert->devise_cible }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $transfert->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

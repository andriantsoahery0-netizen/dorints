<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Fiche utilisateur — {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Informations générales --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="flex items-center gap-4">
                    <span class="flex items-center justify-center w-14 h-14 rounded-full bg-indigo-700 text-white text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                    <div>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <span @class([
                            'inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium',
                            'bg-purple-100 text-purple-800' => $user->role === 'admin',
                            'bg-gray-100 text-gray-800' => $user->role === 'agent',
                        ])>
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Commandes créées --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">
                    Commandes créées ({{ $user->commandes->count() }})
                </h3>
                @if ($user->commandes->isEmpty())
                    <p class="text-gray-500 text-sm">Aucune commande créée par cet utilisateur.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">N°</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($user->commandes as $commande)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        <a href="{{ route('commandes.show', $commande) }}" class="text-indigo-700 hover:underline">
                                            {{ $commande->numero_commande }}
                                        </a>
                                    </td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Transferts enregistrés --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">
                    Transferts enregistrés ({{ $user->transferts->count() }})
                </h3>
                @if ($user->transferts->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun transfert enregistré par cet utilisateur.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant envoyé</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant converti</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($user->transferts as $transfert)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-4 py-3 text-gray-600">{{ $transfert->client->nom }} {{ $transfert->client->prenom }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ number_format($transfert->montant_envoye, 2) }} {{ $transfert->devise_source }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ number_format($transfert->montant_converti, 2) }} {{ $transfert->devise_cible }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $transfert->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
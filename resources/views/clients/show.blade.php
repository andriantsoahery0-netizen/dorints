<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
                Fiche client — {{ $client->nom }} {{ $client->prenom }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('clients.edit', $client) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg text-sm">
                    Modifier
                </a>
                <a href="{{ route('clients.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Informations générales --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Informations</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nom complet</dt>
                        <dd class="text-gray-900">{{ $client->nom }} {{ $client->prenom }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Téléphone</dt>
                        <dd class="text-gray-900">{{ $client->telephone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="text-gray-900">{{ $client->email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Adresse</dt>
                        <dd class="text-gray-900">{{ $client->adresse }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Historique des commandes --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Historique des commandes</h3>
                @if ($client->commandes->isEmpty())
                    <p class="text-gray-500">Aucune commande enregistrée.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($client->commandes as $commande)
                                <tr>
                                    <td class="px-4 py-3">{{ $commande->numero_commande }}</td>
                                    <td class="px-4 py-3">{{ Str::limit($commande->description, 40) }}</td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- Historique des transferts --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Historique des transferts d'argent</h3>
                @if ($client->transferts->isEmpty())
                    <p class="text-gray-500">Aucun transfert enregistré.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant envoyé</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Montant converti</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Taux appliqué</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($client->transferts as $transfert)
                                <tr>
                                    <td class="px-4 py-3">{{ number_format($transfert->montant_envoye, 2) }} {{ $transfert->devise_source }}</td>
                                    <td class="px-4 py-3">{{ number_format($transfert->montant_converti, 2) }} {{ $transfert->devise_cible }}</td>
                                    <td class="px-4 py-3">{{ $transfert->taux_applique }}</td>
                                    <td class="px-4 py-3">{{ $transfert->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
                Commande {{ $commande->numero_commande }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('commandes.edit', $commande) }}"
                   class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                    Modifier
                </a>
                <a href="{{ route('commandes.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Informations générales --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Informations</h3>
                    <span @class([
                        'px-3 py-1 rounded-full text-sm font-medium',
                        'bg-yellow-100 text-yellow-800' => $commande->statut === 'en_attente',
                        'bg-blue-100 text-blue-800' => $commande->statut === 'en_cours',
                        'bg-green-100 text-green-800' => $commande->statut === 'livree',
                    ])>
                        {{ str_replace('_', ' ', $commande->statut) }}
                    </span>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Client</dt>
                        <dd class="text-gray-900 font-medium">
                            <a href="{{ route('clients.show', $commande->client) }}" class="text-indigo-700 hover:underline">
                                {{ $commande->client->nom }} {{ $commande->client->prenom }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Agent créateur</dt>
                        <dd class="text-gray-900 font-medium">{{ $commande->agent->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Date de création</dt>
                        <dd class="text-gray-900 font-medium">{{ $commande->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm text-gray-500">Description</dt>
                        <dd class="text-gray-900">{{ $commande->description }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Transferts liés --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Transferts d'argent liés</h3>
                    <a href="{{ route('transferts.create', $commande) }}"
                       class="flex items-center gap-1.5 bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un transfert
                    </a>
                </div>
                @if ($commande->transferts->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun transfert enregistré pour cette commande.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant envoyé</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Montant converti</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($commande->transferts as $transfert)
                                <tr class="hover:bg-gray-50/70 transition">
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

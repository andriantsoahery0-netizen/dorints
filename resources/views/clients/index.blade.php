<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
                Gestion des Clients
            </h2>
            <a href="{{ route('clients.create') }}"
               class="flex items-center gap-1.5 bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau client
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

            <form action="{{ route('clients.index') }}" method="GET" class="mb-4 flex gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="recherche" value="{{ request('recherche') }}"
                           placeholder="Rechercher par nom, prénom, téléphone, email..."
                           class="w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                </div>
                <button type="submit"
                        class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                    Rechercher
                </button>
                @if (request('recherche'))
                    <a href="{{ route('clients.index') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                        Réinitialiser
                    </a>
                @endif
            </form>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6">

                    @if ($clients->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <p class="text-gray-500">Aucun client enregistré pour le moment.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Prénom</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Téléphone</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Adresse</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($clients as $client)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $client->nom }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $client->prenom }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $client->telephone }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $client->email ?? '—' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $client->adresse }}</td>
                                        <td class="px-4 py-3 text-right space-x-3">
                                            <a href="{{ route('clients.show', $client) }}"
                                               class="text-indigo-700 hover:text-indigo-900 font-medium text-sm">Voir</a>
                                            <a href="{{ route('clients.edit', $client) }}"
                                               class="text-amber-600 hover:text-amber-800 font-medium text-sm">Modifier</a>
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Supprimer ce client ?');">
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
                            {{ $clients->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

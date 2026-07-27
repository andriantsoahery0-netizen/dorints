<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Clients
            </h2>
            <a href="{{ route('clients.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                + Nouveau client
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
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('clients.index') }}" method="GET" class="mb-4 flex gap-2">
                <input type="text" name="recherche" value="{{ request('recherche') }}"
                       placeholder="Rechercher par nom, prénom, téléphone, email..."
                       class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                    Rechercher
                </button>
                @if (request('recherche'))
                    <a href="{{ route('clients.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm">
                        Réinitialiser
                    </a>
                @endif
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($clients->isEmpty())
                        <p class="text-gray-500 text-center py-8">Aucun client enregistré pour le moment.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Prénom</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Adresse</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($clients as $client)
                                    <tr>
                                        <td class="px-4 py-3">{{ $client->nom }}</td>
                                        <td class="px-4 py-3">{{ $client->prenom }}</td>
                                        <td class="px-4 py-3">{{ $client->telephone }}</td>
                                        <td class="px-4 py-3">{{ $client->email ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $client->adresse }}</td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <a href="{{ route('clients.show', $client) }}"
                                               class="text-blue-600 hover:underline text-sm">Voir</a>
                                            <a href="{{ route('clients.edit', $client) }}"
                                               class="text-yellow-600 hover:underline text-sm">Modifier</a>
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Supprimer ce client ?');">
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
                            {{ $clients->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
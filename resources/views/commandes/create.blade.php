<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nouvelle commande
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">

                @if ($clients->isEmpty())
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg text-sm">
                        Aucun client enregistré. <a href="{{ route('clients.create') }}" class="underline font-medium">Créer un client d'abord</a>.
                    </div>
                @else
                    <form action="{{ route('commandes.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                            <select name="client_id" id="client_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                                <option value="">-- Sélectionner un client --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>
                                        {{ $client->nom }} {{ $client->prenom }} — {{ $client->telephone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description de la commande</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <p class="text-xs text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                            Le numéro de commande et le statut ("en attente") seront générés automatiquement.
                        </p>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('commandes.index') }}"
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                                Créer la commande
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

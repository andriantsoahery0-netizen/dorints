<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
            Modifier la commande {{ $commande->numero_commande }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">

                <form action="{{ route('commandes.update', $commande) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                        <select name="client_id" id="client_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('client_id', $commande->client_id) == $client->id)>
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
                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">{{ old('description', $commande->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="statut" id="statut"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            <option value="en_attente" @selected(old('statut', $commande->statut) === 'en_attente')>En attente</option>
                            <option value="en_cours" @selected(old('statut', $commande->statut) === 'en_cours')>En cours</option>
                            <option value="livree" @selected(old('statut', $commande->statut) === 'livree')>Livrée</option>
                        </select>
                        @error('statut')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('commandes.index') }}"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Mettre à jour
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

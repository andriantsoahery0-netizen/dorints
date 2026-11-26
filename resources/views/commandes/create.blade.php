<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Nouvelle commande
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">

                @if ($clients->isEmpty())
                    <div class="bg-slate-50 border border-slate-200 text-slate-700 px-4 py-3 rounded-lg text-sm">
                        Aucun client enregistré. <a href="{{ route('clients.create') }}" class="underline font-medium">Créer un client d'abord</a>.
                    </div>
                @else
                    <form action="{{ route('commandes.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="client_id" class="block text-sm font-medium text-slate-700">Client</label>
                            <select name="client_id" id="client_id"
                                    class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
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

                        @include('commandes._lignes')

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700">Remarque <span class="text-slate-400 font-normal">(facultatif)</span></label>
                            <textarea name="description" id="description" rows="2"
                                      class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <p class="text-xs text-slate-500 bg-slate-50 rounded-lg px-3 py-2">
                            Le numéro de commande, le montant total et le statut ("en attente") seront générés automatiquement.
                        </p>

                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('commandes.index') }}"
                               class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                                Créer la commande
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

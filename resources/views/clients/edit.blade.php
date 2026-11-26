<x-app-layout>
    <x-slot name="header">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Modifier le client
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nom" class="block text-sm font-medium text-slate-700">Nom</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $client->nom) }}"
                               class="mt-1 block w-full uppercase rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('nom')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-slate-700">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $client->prenom) }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('prenom')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-slate-700">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" placeholder="034 12 345 67" value="{{ old('telephone', $client->telephone) }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        <p class="text-xs text-slate-500 mt-1">Enregistré au format 034 12 345 67, quelle que soit la saisie.</p>
                        @error('telephone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="adresse" class="block text-sm font-medium text-slate-700">Adresse</label>
                        <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $client->adresse) }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('adresse')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email (facultatif)</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('clients.index') }}"
                           class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-medium py-2 px-4 rounded-lg text-sm">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm">
                            Mettre à jour
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
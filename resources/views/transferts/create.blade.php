<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nouveau transfert — Commande {{ $commande->numero_commande }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Rappel du taux actuel --}}
            <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    Taux actuel : <strong>1 EUR = {{ $tauxActuel->taux_eur_vers_mga }} MGA</strong>
                    ({{ $tauxActuel->taux_mga_vers_eur }} EUR pour 1 MGA)
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500 mb-4">
                    Client : <strong>{{ $commande->client->nom }} {{ $commande->client->prenom }}</strong>
                </p>

                <form action="{{ route('transferts.store', $commande) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="sens" class="block text-sm font-medium text-gray-700">Sens de la conversion</label>
                        <select name="sens" id="sens"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="eur_vers_mga" @selected(old('sens') === 'eur_vers_mga')>Euro → Ariary (EUR vers MGA)</option>
                            <option value="mga_vers_eur" @selected(old('sens') === 'mga_vers_eur')>Ariary → Euro (MGA vers EUR)</option>
                        </select>
                        @error('sens')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="montant_envoye" class="block text-sm font-medium text-gray-700">Montant envoyé</label>
                        <input type="text" inputmode="decimal" name="montant_envoye" id="montant_envoye"
                               value="{{ old('montant_envoye') }}"
                               placeholder="ex: 100"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('montant_envoye')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-xs text-gray-500">Le montant converti sera calculé automatiquement avec le taux actuel.</p>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('commandes.show', $commande) }}"
                           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg text-sm">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                            Enregistrer le transfert
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
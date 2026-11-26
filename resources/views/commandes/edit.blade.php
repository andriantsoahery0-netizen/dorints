<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Modifier la commande {{ $commande->numero_commande }}
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">

                <form action="{{ route('commandes.update', $commande) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    @if ($commande->facture)
                        {{-- Commande facturée : produits et prix figés, seul le statut se modifie --}}
                        <div class="bg-slate-50 border border-slate-200 text-slate-700 px-4 py-3 rounded-lg text-sm">
                            Cette commande est facturée ({{ $commande->facture->numero_facture }}) : ses produits et ses prix ne peuvent plus être modifiés.
                        </div>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-slate-500 uppercase border-b border-slate-200">
                                    <th class="py-2">Produit</th>
                                    <th class="py-2 text-right">Quantité</th>
                                    <th class="py-2 text-right">Prix unitaire</th>
                                    <th class="py-2 text-right">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commande->lignes as $ligne)
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2">{{ $ligne->designation }}</td>
                                        <td class="py-2 text-right">{{ $ligne->quantite }}</td>
                                        <td class="py-2 text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} Ar</td>
                                        <td class="py-2 text-right">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} Ar</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-slate-700">Client</label>
                            <select name="client_id" id="client_id"
                                    class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
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

                        @include('commandes._lignes', [
                            'lignes' => $commande->lignes->map(fn ($l) => [
                                'designation' => $l->designation,
                                'quantite' => $l->quantite,
                                'prix_unitaire' => (float) $l->prix_unitaire,
                            ])->all(),
                        ])

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700">Remarque <span class="text-slate-400 font-normal">(facultatif)</span></label>
                            <textarea name="description" id="description" rows="2"
                                      class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">{{ old('description', $commande->description) }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="statut" class="block text-sm font-medium text-slate-700">Statut</label>
                        <select name="statut" id="statut"
                                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="en_attente" @selected(old('statut', $commande->statut) === 'en_attente')>En attente</option>
                            <option value="validee" @selected(old('statut', $commande->statut) === 'validee')>Validée</option>
                            <option value="en_cours" @selected(old('statut', $commande->statut) === 'en_cours')>En cours</option>
                            <option value="livree" @selected(old('statut', $commande->statut) === 'livree')>Livrée</option>
                        </select>
                        @error('statut')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('commandes.show', $commande) }}"
                           class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Mettre à jour
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

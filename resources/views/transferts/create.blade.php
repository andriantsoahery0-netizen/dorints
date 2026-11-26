<x-app-layout>
    <x-slot name="header">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Règlement de la facture {{ $commande->facture->numero_facture }} — Commande {{ $commande->numero_commande }}
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8">

            {{-- Rappel du taux actuel --}}
            <div class="mb-4 bg-blue-50 border border-blue-100 rounded-xl p-4">
                <p class="text-sm text-blue-900">
                    Taux actuel : <strong>1 EUR = {{ $tauxActuel->taux_eur_vers_mga }} MGA</strong>
                    <span class="text-blue-600">({{ $tauxActuel->taux_mga_vers_eur }} EUR pour 1 MGA)</span>
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <div class="grid grid-cols-3 gap-4 mb-5 pb-4 border-b border-slate-100 text-sm">
                    <div><p class="text-slate-500">Client</p>
                         <p class="font-semibold text-slate-800">{{ $commande->client->nom }} {{ $commande->client->prenom }}</p></div>
                    <div><p class="text-slate-500">Montant de la facture</p>
                         <p class="font-semibold text-slate-800">{{ number_format($commande->facture->montant, 0, ',', ' ') }} Ar</p></div>
                    <div><p class="text-slate-500">Reste à payer</p>
                         <p class="font-semibold text-red-700">{{ number_format($commande->resteAPayer(), 0, ',', ' ') }} Ar</p></div>
                </div>

                <form action="{{ route('transferts.store', $commande) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="sens" class="block text-sm font-medium text-slate-700">Sens de la conversion</label>
                        <select name="sens" id="sens"
                                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="mga_vers_eur" @selected(old('sens') === 'mga_vers_eur')>Ariary → Euro (MGA vers EUR)</option>
                            <option value="eur_vers_mga" @selected(old('sens') === 'eur_vers_mga')>Euro → Ariary (EUR vers MGA)</option>
                        </select>
                        @error('sens')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="montant_envoye" class="block text-sm font-medium text-slate-700">Montant envoyé</label>
                        <input type="text" inputmode="decimal" name="montant_envoye" id="montant_envoye"
                               value="{{ old('montant_envoye') }}"
                               placeholder="Saisissez le montant reçu du client"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        <p class="text-xs text-slate-500 mt-1">Reste à payer : {{ number_format($commande->resteAPayer(), 0, ',', ' ') }} Ar. Le montant n'est pas prérempli : saisissez celui réellement reçu.</p>
                        @error('montant_envoye')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mode_paiement" class="block text-sm font-medium text-slate-700">Mode de paiement du client</label>
                        <select name="mode_paiement" id="mode_paiement"
                                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="especes" @selected(old('mode_paiement') === 'especes')>Espèces</option>
                            <option value="mvola" @selected(old('mode_paiement') === 'mvola')>MVola</option>
                            <option value="orange_money" @selected(old('mode_paiement') === 'orange_money')>Orange Money</option>
                            <option value="airtel_money" @selected(old('mode_paiement') === 'airtel_money')>Airtel Money</option>
                            <option value="virement" @selected(old('mode_paiement') === 'virement')>Virement bancaire</option>
                        </select>
                        @error('mode_paiement')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <p class="text-xs text-slate-500 bg-slate-50 rounded-lg px-3 py-2">
                        Un règlement en ariary est déduit du reste à payer de la facture. Le montant converti est calculé automatiquement avec le taux actuel.
                    </p>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('commandes.show', $commande) }}"
                           class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Enregistrer le règlement
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

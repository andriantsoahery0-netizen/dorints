{{-- Produits commandés : désignation, quantité, prix unitaire. Le total se calcule en direct. --}}
@php
    $lignesInit = array_values(old('lignes', $lignes ?? [['designation' => '', 'quantite' => 1, 'prix_unitaire' => '']]));
@endphp
<div x-data="{
        lignes: @js($lignesInit),
        ajouter() { this.lignes.push({ designation: '', quantite: 1, prix_unitaire: '' }) },
        retirer(i) { if (this.lignes.length > 1) this.lignes.splice(i, 1) },
        montant(l) { return (parseFloat(l.quantite) || 0) * (parseFloat(l.prix_unitaire) || 0) },
        get total() { return this.lignes.reduce((t, l) => t + this.montant(l), 0) },
        ar(v) { return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(v) + ' Ar' }
     }">
    <label class="block text-sm font-medium text-slate-700 mb-1">Produits commandés</label>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-slate-500 uppercase">
                <th class="py-2 pr-2">Produit</th>
                <th class="py-2 pr-2 w-24">Quantité</th>
                <th class="py-2 pr-2 w-40">Prix unitaire (Ar)</th>
                <th class="py-2 pr-2 w-32 text-right">Montant</th>
                <th class="py-2 w-8"></th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(ligne, i) in lignes" :key="i">
                <tr>
                    <td class="py-1 pr-2">
                        <input type="text" :name="`lignes[${i}][designation]`" x-model="ligne.designation"
                               placeholder="ex : Smartphone Samsung Galaxy A15"
                               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-600 focus:ring-blue-600">
                    </td>
                    <td class="py-1 pr-2">
                        <input type="number" min="1" step="1" :name="`lignes[${i}][quantite]`" x-model="ligne.quantite"
                               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-600 focus:ring-blue-600">
                    </td>
                    <td class="py-1 pr-2">
                        <input type="number" min="0" step="0.01" :name="`lignes[${i}][prix_unitaire]`" x-model="ligne.prix_unitaire"
                               placeholder="ex : 850000"
                               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-600 focus:ring-blue-600">
                    </td>
                    <td class="py-1 pr-2 text-right font-medium text-slate-800 whitespace-nowrap" x-text="ar(montant(ligne))"></td>
                    <td class="py-1 text-right">
                        <button type="button" @click="retirer(i)" x-show="lignes.length > 1"
                                class="text-red-500 hover:text-red-700" title="Retirer ce produit">&times;</button>
                    </td>
                </tr>
            </template>
        </tbody>
        <tfoot>
            <tr class="border-t border-slate-200">
                <td colspan="3" class="pt-3">
                    <button type="button" @click="ajouter()"
                            class="text-blue-700 hover:text-blue-900 font-medium text-sm">+ Ajouter un produit</button>
                </td>
                <td class="pt-3 pr-2 text-right font-semibold text-slate-900 whitespace-nowrap" x-text="ar(total)"></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    @if ($errors->has('lignes') || $errors->has('lignes.*'))
        <ul class="text-red-600 text-sm mt-2 list-disc list-inside">
            @foreach (array_unique(array_merge($errors->get('lignes'), ...array_values($errors->get('lignes.*')))) as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif
</div>

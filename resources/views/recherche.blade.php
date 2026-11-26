<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Recherche @if ($q !== '') : « {{ $q }} » @endif
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($q === '')
                <p class="text-slate-600">Tapez un nom de client, un numéro de commande ou de facture, ou un produit dans la barre de recherche.</p>
            @elseif ($clients->isEmpty() && $commandes->isEmpty() && $factures->isEmpty())
                <p class="text-slate-600">Aucun résultat pour « {{ $q }} ».</p>
            @endif

            @if ($clients->isNotEmpty())
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="border-b border-slate-200 px-5 py-3 font-display text-lg font-bold text-slate-900">Clients ({{ $clients->count() }})</h3>
                    @foreach ($clients as $client)
                        <a href="{{ route('clients.show', $client) }}" class="flex justify-between gap-4 border-b border-slate-100 px-5 py-3 text-sm last:border-0 hover:bg-slate-50">
                            <span class="font-medium text-slate-900">{{ $client->nom }} {{ $client->prenom }}</span>
                            <span class="text-slate-600">{{ $client->telephone }}</span>
                        </a>
                    @endforeach
                </section>
            @endif

            @if ($commandes->isNotEmpty())
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="border-b border-slate-200 px-5 py-3 font-display text-lg font-bold text-slate-900">Commandes ({{ $commandes->count() }})</h3>
                    @foreach ($commandes as $commande)
                        <a href="{{ route('commandes.show', $commande) }}" class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-3 text-sm last:border-0 hover:bg-slate-50">
                            <span><span class="font-medium text-slate-900">{{ $commande->numero_commande }}</span> <span class="text-slate-600">· {{ $commande->client->nom }} {{ $commande->client->prenom }}</span></span>
                            <span class="flex items-center gap-3"><span class="whitespace-nowrap">{{ number_format($commande->montant_total, 0, ',', ' ') }} Ar</span><x-statut :statut="$commande->statut" /></span>
                        </a>
                    @endforeach
                </section>
            @endif

            @if ($factures->isNotEmpty())
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="border-b border-slate-200 px-5 py-3 font-display text-lg font-bold text-slate-900">Factures ({{ $factures->count() }})</h3>
                    @foreach ($factures as $facture)
                        <a href="{{ route('factures.show', $facture) }}" class="flex flex-wrap justify-between gap-4 border-b border-slate-100 px-5 py-3 text-sm last:border-0 hover:bg-slate-50">
                            <span><span class="font-medium text-slate-900">{{ $facture->numero_facture }}</span> <span class="text-slate-600">· {{ $facture->commande->client->nom }} {{ $facture->commande->client->prenom }}</span></span>
                            <span class="whitespace-nowrap">{{ number_format($facture->montant, 0, ',', ' ') }} Ar</span>
                        </a>
                    @endforeach
                </section>
            @endif
        </div>
    </div>
</x-app-layout>

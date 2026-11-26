@php
    $ar = fn ($v) => number_format($v, 0, ',', ' ') . ' Ar';
    $prenom = auth()->user()->prenom ?: auth()->user()->name;
    $nomMois = now()->locale('fr')->translatedFormat('F');
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">Bonjour, {{ $prenom }}</h2>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ auth()->user()->isAdmin() ? 'Administrateur' : 'Agent' }} · {{ now()->locale('fr')->translatedFormat('l j F Y') }}
                    </p>
                </div>
                <a href="{{ route('commandes.create') }}" class="rounded-lg bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-900">
                    + Nouvelle commande
                </a>
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            {{-- Indicateurs --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3"><p class="text-sm font-medium text-slate-600">Facturé · {{ $nomMois }}</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10v18l-3-2-2 2-2-2-3 2zM9 8h6M9 12h6" /></svg></span></div>
                    <p class="mt-1 font-display text-2xl font-bold text-slate-900">{{ $ar($stats['facture_mois']) }}</p>
                    @if ($taux)
                        <p class="mt-1 text-xs text-slate-600">≈ {{ number_format($stats['facture_mois'] * $taux->taux_mga_vers_eur, 0, ',', ' ') }} € au taux du jour</p>
                    @endif
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3"><p class="text-sm font-medium text-slate-600">Encaissé · {{ $nomMois }}</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18v10H3zM12 12m-2 0a2 2 0 104 0 2 2 0 10-4 0M6 10v4M18 10v4" /></svg></span></div>
                    <p class="mt-1 font-display text-2xl font-bold text-slate-900">{{ $ar($stats['encaisse_mois']) }}</p>
                    <div class="mt-3 h-1.5 rounded-full bg-slate-200" title="{{ $stats['pourcentage_encaisse'] }} % du facturé">
                        <div class="h-1.5 rounded-full bg-blue-600" style="width: {{ $stats['pourcentage_encaisse'] }}%"></div>
                    </div>
                </div>
                <a href="{{ route('factures.index', ['etat' => 'non_reglees']) }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-400">
                    <div class="flex items-start justify-between gap-3"><p class="text-sm font-medium text-slate-600">Reste à encaisser</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span></div>
                    <p class="mt-1 font-display text-2xl font-bold text-red-700">{{ $ar($stats['reste_a_encaisser']) }}</p>
                    <p class="mt-1 text-xs text-slate-600">{{ $stats['factures_ouvertes'] }} facture(s) ouverte(s)</p>
                </a>
                <a href="{{ route('clients.index') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-400">
                    <div class="flex items-start justify-between gap-3"><p class="text-sm font-medium text-slate-600">Clients</p><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11a4 4 0 100-8 4 4 0 000 8zM2 21c0-4 3-6 7-6s7 2 7 6M17 11a3 3 0 100-6M22 21c0-3-2-5-5-5.5" /></svg></span></div>
                    <p class="mt-1 font-display text-2xl font-bold text-slate-900">{{ $stats['clients'] }}</p>
                    <p class="mt-1 text-xs text-slate-600">{{ $stats['clients_a_jour'] }} à jour · {{ $stats['clients_a_relancer'] }} à relancer</p>
                </a>
            </div>

            {{-- Circuit des commandes --}}
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-3">
                    <h3 class="font-display text-lg font-bold text-slate-900">Circuit des commandes</h3>
                </div>
                <div class="grid grid-cols-2 gap-3 p-5 lg:grid-cols-4">
                    <a href="{{ route('commandes.index', ['statut' => 'en_attente']) }}" class="rounded-lg border border-slate-200 bg-slate-50/60 p-4 text-slate-900 transition hover:border-blue-400 hover:bg-blue-50/40">
                        <p class="flex items-center gap-2 text-sm font-semibold"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-800">1</span>En attente</p>
                        <p class="mt-2 font-display text-3xl font-bold text-blue-800">{{ $stats['commandes_en_attente'] }}</p>
                        <p class="text-xs text-slate-600">à valider</p>
                    </a>
                    <a href="{{ route('commandes.index', ['statut' => 'validee']) }}" class="rounded-lg border border-slate-200 bg-slate-50/60 p-4 text-slate-900 transition hover:border-blue-400 hover:bg-blue-50/40">
                        <p class="flex items-center gap-2 text-sm font-semibold"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-800">2</span>Validées</p>
                        <p class="mt-2 font-display text-3xl font-bold text-blue-800">{{ $stats['commandes_validees'] }}</p>
                        <p class="text-xs text-slate-600">facturées</p>
                    </a>
                    <a href="{{ route('commandes.index', ['statut' => 'en_cours']) }}" class="rounded-lg border border-slate-200 bg-slate-50/60 p-4 text-slate-900 transition hover:border-blue-400 hover:bg-blue-50/40">
                        <p class="flex items-center gap-2 text-sm font-semibold"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-800">3</span>En cours</p>
                        <p class="mt-2 font-display text-3xl font-bold text-blue-800">{{ $stats['commandes_en_cours'] }}</p>
                        <p class="text-xs text-slate-600">achat en Europe</p>
                    </a>
                    <a href="{{ route('commandes.index', ['statut' => 'livree']) }}" class="rounded-lg border border-slate-200 bg-slate-50/60 p-4 text-slate-900 transition hover:border-blue-400 hover:bg-blue-50/40">
                        <p class="flex items-center gap-2 text-sm font-semibold"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-800">4</span>Livrées</p>
                        <p class="mt-2 font-display text-3xl font-bold text-blue-800">{{ $stats['commandes_livrees'] }}</p>
                        <p class="text-xs text-slate-600">terminées</p>
                    </a>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Commandes à valider --}}
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-3">
                        <h3 class="font-display text-lg font-bold text-slate-900">Commandes à valider</h3>
                        <a href="{{ route('commandes.index', ['statut' => 'en_attente']) }}" class="text-sm text-blue-700 hover:underline">Tout voir</a>
                    </div>
                    @forelse ($aValider as $commande)
                        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3 text-sm last:border-0">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('commandes.show', $commande) }}" class="font-medium text-slate-900 hover:underline">{{ $commande->numero_commande }}</a>
                                <p class="break-words text-xs text-slate-600">{{ $commande->client->nom }} {{ $commande->client->prenom }} · {{ $commande->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="whitespace-nowrap font-medium">{{ $ar($commande->montant_total) }}</span>
                            <form method="POST" action="{{ route('commandes.valider', $commande) }}">
                                @csrf
                                <button type="submit" class="rounded-lg bg-blue-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-900">Valider</button>
                            </form>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-center text-sm text-slate-500">Aucune commande en attente.</p>
                    @endforelse
                </section>

                {{-- Factures à relancer --}}
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-3">
                        <h3 class="font-display text-lg font-bold text-slate-900">Clients à relancer</h3>
                        <a href="{{ route('factures.index', ['etat' => 'non_reglees']) }}" class="text-sm text-blue-700 hover:underline">Toutes les factures</a>
                    </div>
                    @forelse ($aRelancer as $facture)
                        @php $client = $facture->commande->client; $sp = $facture->commande->statutPaiement(); @endphp
                        <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3 last:border-0">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-semibold bg-blue-50 text-blue-700">
                                {{ strtoupper(substr($client->nom, 0, 1) . substr($client->prenom, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('clients.show', $client) }}" class="block break-words text-sm font-semibold text-slate-900 hover:underline">{{ $client->nom }} {{ $client->prenom }}</a>
                                <p class="text-xs text-slate-600">{{ $facture->numero_facture }} · {{ $sp === 'partiel' ? 'partielle' : 'non réglée' }}</p>
                                @if ($facture->derniere_relance_at)
                                    <p class="text-xs text-slate-500">Relancé le {{ $facture->derniere_relance_at->format('d/m/Y à H:i') }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="whitespace-nowrap text-sm font-bold text-red-700">{{ $ar($facture->commande->resteAPayer()) }}</p>
                                <div class="mt-1 flex items-center justify-end gap-3 text-xs font-semibold">
                                    <a href="{{ route('transferts.create', $facture->commande) }}" class="whitespace-nowrap text-blue-700 hover:underline">Règlement</a>

                                    {{-- Relancer le client : e-mail ou WhatsApp --}}
                                    <div x-data="{ ouvert: false }" class="relative" @click.outside="ouvert = false">
                                        <button type="button" @click="ouvert = ! ouvert" class="flex items-center gap-1 whitespace-nowrap rounded-md border border-slate-300 px-2 py-1 text-slate-700 hover:bg-slate-50">
                                            Relancer
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" d="M6 9l6 6 6-6" /></svg>
                                        </button>
                                        <div x-show="ouvert" x-transition style="display: none" class="absolute right-0 z-20 mt-1 w-56 rounded-lg border border-slate-200 bg-white py-1 text-left text-sm font-normal shadow-lg">
                                            @if ($client->email)
                                                <form method="POST" action="{{ route('factures.relancer-email', $facture) }}">
                                                    @csrf
                                                    <button type="submit" class="block w-full px-4 py-2 text-left text-slate-700 hover:bg-slate-50">
                                                        Par e-mail
                                                        <span class="block break-all text-xs text-slate-500">{{ $client->email }}</span>
                                                    </button>
                                                </form>
                                            @else
                                                <p class="px-4 py-2 text-slate-400">Par e-mail<span class="block text-xs">Pas d'adresse e-mail</span></p>
                                            @endif
                                            <a href="{{ route('factures.relancer-whatsapp', $facture) }}" target="_blank" rel="noopener"
                                               @click="setTimeout(() => window.location.reload(), 1500)"
                                               class="block px-4 py-2 text-slate-700 hover:bg-slate-50">
                                                Par WhatsApp
                                                <span class="block text-xs text-slate-500">{{ $client->telephone }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-center text-sm text-slate-500">Toutes les factures sont réglées.</p>
                    @endforelse
                </section>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Graphique des 6 derniers mois --}}
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-3">
                        <h3 class="font-display text-lg font-bold text-slate-900">Facturé et encaissé · 6 derniers mois</h3>
                    </div>
                    <div class="p-5">
                    <div class="flex h-44 items-end gap-3 border-b border-slate-200 px-2">
                        @foreach ($mois as $m)
                            <div class="flex h-full flex-1 items-end justify-center gap-1">
                                <div class="w-1/3 max-w-[22px] rounded-t bg-blue-800" style="height: {{ max(1, $m['h_facture']) }}%" title="Facturé : {{ $ar($m['facture']) }}"></div>
                                <div class="w-1/3 max-w-[22px] rounded-t bg-blue-300" style="height: {{ max(1, $m['h_encaisse']) }}%" title="Encaissé : {{ $ar($m['encaisse']) }}"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex gap-3 px-2 pt-2 text-center text-xs text-slate-600">
                        @foreach ($mois as $m)
                            <span class="flex-1">{{ $m['libelle'] }}</span>
                        @endforeach
                    </div>
                    <div class="mt-3 flex gap-4 text-xs text-slate-700">
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-blue-800"></span>Facturé</span>
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-blue-300"></span>Encaissé</span>
                    </div>
                    </div>
                </section>


                {{-- Dernières commandes --}}
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-3">
                        <h3 class="font-display text-lg font-bold text-slate-900">Dernières commandes</h3>
                        <a href="{{ route('commandes.index') }}" class="text-sm text-blue-700 hover:underline">Toutes les commandes</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[400px] table-fixed text-sm">
                            <colgroup><col><col class="w-[8.5rem]"><col class="w-[7.5rem]"></colgroup>
                            <thead>
                                <tr class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-2 font-semibold text-left">Commande et client</th>
                                    <th class="px-3 py-2 font-semibold text-right">Montant</th>
                                    <th class="px-3 py-2 font-semibold text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($dernieres as $commande)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-5 py-3 text-left">
                                            <a href="{{ route('commandes.show', $commande) }}" class="font-medium text-slate-900 hover:underline">{{ $commande->numero_commande }}</a>
                                            <span class="block break-words text-xs text-slate-500">{{ $commande->client->nom }} {{ $commande->client->prenom }}</span>
                                        </td>
                                        <td class="px-3 py-3 text-right whitespace-nowrap">{{ $ar($commande->montant_total) }}</td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap"><x-statut :statut="$commande->statut" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>

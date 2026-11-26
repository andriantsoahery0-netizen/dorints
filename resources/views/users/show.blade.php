<x-app-layout>
    <x-slot name="header">
        <div class="max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Fiche utilisateur — {{ $user->name }}
                </h2>
                <div class="flex items-center gap-2">
                    @if ($user->id !== auth()->id())
                        <form action="{{ route('users.nouveau-ticket', $user) }}" method="POST"
                              onsubmit="return confirm('Générer un nouveau mot de passe provisoire et réimprimer le ticket ? L\'ancien mot de passe ne fonctionnera plus.');">
                            @csrf
                            <button type="submit"
                                    class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                                Réimprimer le ticket
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('users.index') }}"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                        Retour
                    </a>
                </div>
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Informations générales --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <div class="flex items-center gap-4">
                    <span class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-700 text-white text-xl font-bold">
                        {{ mb_strtoupper(mb_substr($user->prenom ?: $user->name, 0, 1)) }}
                    </span>
                    <div>
                        <p class="text-lg font-semibold text-slate-800">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                        <span @class([
                            'inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium',
                            'bg-blue-800 text-white' => $user->role === 'admin',
                            'bg-slate-100 text-slate-800' => $user->role === 'agent',
                        ])>
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Commandes créées --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">
                    Commandes créées ({{ $user->commandes->count() }})
                </h3>
                @if ($user->commandes->isEmpty())
                    <p class="text-slate-500 text-sm">Aucune commande créée par cet utilisateur.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[620px] table-fixed divide-y divide-slate-100">
                            <colgroup><col class="w-[10.5rem]"><col><col class="w-[8rem]"><col class="w-[7.5rem]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">N°</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Client</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Statut</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($user->commandes as $commande)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-3 py-3 font-medium text-slate-800 text-left whitespace-nowrap">
                                            <a href="{{ route('commandes.show', $commande) }}" class="text-blue-700 hover:underline">
                                                {{ $commande->numero_commande }}
                                            </a>
                                        </td>
                                        <td class="px-3 py-3 text-slate-600 text-left break-words">{{ $commande->client->nom }} {{ $commande->client->prenom }}</td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            <x-statut :statut="$commande->statut" />
                                        </td>
                                        <td class="px-3 py-3 text-slate-500 text-center whitespace-nowrap">{{ $commande->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Transferts enregistrés --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">
                    Transferts enregistrés ({{ $user->transferts->count() }})
                </h3>
                @if ($user->transferts->isEmpty())
                    <p class="text-slate-500 text-sm">Aucun transfert enregistré par cet utilisateur.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[560px] table-fixed divide-y divide-slate-100">
                            <colgroup><col class="w-[31%]"><col class="w-[25%]"><col class="w-[22%]"><col class="w-[22%]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Client</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant envoyé</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Montant converti</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($user->transferts as $transfert)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-3 py-3 text-slate-600 text-left break-words">{{ $transfert->client->nom }} {{ $transfert->client->prenom }}</td>
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ number_format($transfert->montant_envoye, 2, ',', ' ') }} {{ $transfert->devise_source }}</td>
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ number_format($transfert->montant_converti, 2, ',', ' ') }} {{ $transfert->devise_cible }}</td>
                                        <td class="px-3 py-3 text-slate-500 text-center whitespace-nowrap">{{ $transfert->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
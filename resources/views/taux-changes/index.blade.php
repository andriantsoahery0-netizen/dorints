<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Gestion du Taux de Change
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Taux actuel --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
                                <div class="relative">
                    <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Taux actuellement appliqué</h3>

                    @if ($tauxActuel)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                <p class="text-sm text-slate-600">1 Ariary (MGA) =</p>
                                <p class="font-display text-2xl font-bold text-blue-900">{{ $tauxActuel->taux_mga_vers_eur }} EUR</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                <p class="text-sm text-slate-600">1 Euro (EUR) =</p>
                                <p class="font-display text-2xl font-bold text-blue-900">{{ number_format($tauxActuel->taux_eur_vers_mga, 0, ',', ' ') }} MGA</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600">
                            Défini par {{ $tauxActuel->admin->name }} le {{ $tauxActuel->created_at->format('d/m/Y à H:i') }}
                        </p>
                    @else
                        <p class="text-red-700">Aucun taux de change n'est actuellement défini. Merci d'en créer un ci-dessous.</p>
                    @endif
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                {{-- Formulaire nouveau taux --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                    <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Modifier le taux de change</h3>

                    <form action="{{ route('taux-changes.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="taux_eur_vers_mga" class="block text-sm font-medium text-slate-700">
                                1 Euro (EUR) = ? Ariary (MGA)
                            </label>
                            <input type="text" inputmode="decimal" name="taux_eur_vers_mga" id="taux_eur_vers_mga"
                                   value="{{ old('taux_eur_vers_mga') }}"
                                   placeholder="ex: 4800"
                                   class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            @error('taux_eur_vers_mga')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">Le taux Ariary → Euro sera calculé automatiquement.</p>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                    class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                                Enregistrer le nouveau taux
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Historique --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <h3 class="mb-4 font-display text-lg font-bold text-slate-900">Historique des taux appliqués</h3>

                @if ($historique->isEmpty())
                    <p class="text-slate-500 text-sm">Aucun historique pour le moment.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[560px] table-fixed divide-y divide-slate-100">
                            <colgroup><col class="w-[25%]"><col class="w-[25%]"><col class="w-[28%]"><col class="w-[22%]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">MGA → EUR</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">EUR → MGA</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Défini par</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($historique as $taux)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-3 py-3 text-slate-600 text-right whitespace-nowrap">{{ $taux->taux_mga_vers_eur }}</td>
                                        <td class="px-3 py-3 text-slate-800 font-medium text-right whitespace-nowrap">{{ $taux->taux_eur_vers_mga }}</td>
                                        <td class="px-3 py-3 text-slate-600 text-left break-words">{{ $taux->admin->name }}</td>
                                        <td class="px-3 py-3 text-slate-500 text-center whitespace-nowrap">{{ $taux->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $historique->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

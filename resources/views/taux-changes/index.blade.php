<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
            Gestion du Taux de Change
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Taux actuel --}}
            <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 rounded-2xl p-6 sm:p-8 shadow-lg overflow-hidden relative">
                <div class="absolute top-0 right-0 w-48 h-48 bg-amber-400/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <h3 class="text-indigo-300 text-sm font-medium uppercase tracking-wider mb-4">Taux actuellement appliqué</h3>

                    @if ($tauxActuel)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/10">
                                <p class="text-sm text-indigo-200">1 Ariary (MGA) =</p>
                                <p class="text-2xl font-bold text-white">{{ $tauxActuel->taux_mga_vers_eur }} EUR</p>
                            </div>
                            <div class="bg-amber-400/10 backdrop-blur rounded-xl p-4 border border-amber-400/20">
                                <p class="text-sm text-amber-200">1 Euro (EUR) =</p>
                                <p class="text-2xl font-bold text-amber-300">{{ $tauxActuel->taux_eur_vers_mga }} MGA</p>
                            </div>
                        </div>
                        <p class="text-sm text-indigo-300">
                            Défini par {{ $tauxActuel->admin->name }} le {{ $tauxActuel->created_at->format('d/m/Y à H:i') }}
                        </p>
                    @else
                        <p class="text-red-300">Aucun taux de change n'est actuellement défini. Merci d'en créer un ci-dessous.</p>
                    @endif
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                {{-- Formulaire nouveau taux --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Modifier le taux de change</h3>

                    <form action="{{ route('taux-changes.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="taux_eur_vers_mga" class="block text-sm font-medium text-gray-700">
                                1 Euro (EUR) = ? Ariary (MGA)
                            </label>
                            <input type="text" inputmode="decimal" name="taux_eur_vers_mga" id="taux_eur_vers_mga"
                                   value="{{ old('taux_eur_vers_mga') }}"
                                   placeholder="ex: 4800"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            @error('taux_eur_vers_mga')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Le taux Ariary → Euro sera calculé automatiquement.</p>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                    class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                                Enregistrer le nouveau taux
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Historique --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Historique des taux appliqués</h3>

                @if ($historique->isEmpty())
                    <p class="text-gray-500 text-sm">Aucun historique pour le moment.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">MGA → EUR</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">EUR → MGA</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Défini par</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($historique as $taux)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-4 py-3 text-gray-600">{{ $taux->taux_mga_vers_eur }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $taux->taux_eur_vers_mga }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $taux->admin->name }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $taux->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-6">
                        {{ $historique->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

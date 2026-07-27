<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion du Taux de Change
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Taux actuel --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Taux actuellement appliqué</h3>

                @if ($tauxActuel)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">1 Ariary (MGA) =</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $tauxActuel->taux_mga_vers_eur }} EUR</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">1 Euro (EUR) =</p>
                            <p class="text-2xl font-bold text-green-700">{{ $tauxActuel->taux_eur_vers_mga }} MGA</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">
                        Défini par {{ $tauxActuel->admin->name }} le {{ $tauxActuel->created_at->format('d/m/Y à H:i') }}
                    </p>
                @else
                    <p class="text-red-600">Aucun taux de change n'est actuellement défini. Merci d'en créer un ci-dessous.</p>
                @endif
            </div>

            @if (auth()->user()->isAdmin())
            {{-- Formulaire nouveau taux --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Modifier le taux de change</h3>

                <form action="{{ route('taux-changes.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="taux_eur_vers_mga" class="block text-sm font-medium text-gray-700">
                            1 Euro (EUR) = ? Ariary (MGA)
                        </label>
                        <input type="text" inputmode="decimal" name="taux_eur_vers_mga" id="taux_eur_vers_mga"
                               value="{{ old('taux_eur_vers_mga') }}"
                               placeholder="ex: 4800"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('taux_eur_vers_mga')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Le taux Ariary → Euro sera calculé automatiquement.</p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                            Enregistrer le nouveau taux
                        </button>
                    </div>
                </form>
            </div>
        @endif

            {{-- Historique --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Historique des taux appliqués</h3>

                @if ($historique->isEmpty())
                    <p class="text-gray-500">Aucun historique pour le moment.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">MGA → EUR</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">EUR → MGA</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Défini par</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($historique as $taux)
                                <tr>
                                    <td class="px-4 py-3">{{ $taux->taux_mga_vers_eur }}</td>
                                    <td class="px-4 py-3">{{ $taux->taux_eur_vers_mga }}</td>
                                    <td class="px-4 py-3">{{ $taux->admin->name }}</td>
                                    <td class="px-4 py-3">{{ $taux->created_at->format('d/m/Y à H:i') }}</td>
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
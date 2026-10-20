<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
            Journal d'activité
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filtres --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Utilisateur</label>
                        <select name="user_id" id="user_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            <option value="">Tous</option>
                            @foreach ($utilisateurs as $utilisateur)
                                <option value="{{ $utilisateur->id }}" @selected(request('user_id') == $utilisateur->id)>
                                    {{ $utilisateur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                        <select name="action" id="action"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            <option value="">Toutes</option>
                            @foreach ($actions as $action)
                                <option value="{{ $action }}" @selected(request('action') == $action)>
                                    {{ str_replace('_', ' ', $action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">Du</label>
                        <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">Au</label>
                        <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                    </div>

                    <div class="sm:col-span-4 flex gap-3">
                        <button type="submit"
                                class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Filtrer
                        </button>
                        <a href="{{ route('activity-logs.index') }}"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tableau des logs --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6">

                    @if ($logs->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500">Aucune activité trouvée.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach ($logs as $log)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $log->user->name ?? 'Utilisateur supprimé' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                                {{ str_replace('_', ' ', $log->action) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $log->description }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $log->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-6">
                            {{ $logs->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

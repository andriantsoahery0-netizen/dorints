<x-app-layout>
    <x-slot name="header">
        <div class="max-w-6xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Journal d'activité
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filtres --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">
                <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-slate-700">Utilisateur</label>
                        <select name="user_id" id="user_id"
                                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="">Tous</option>
                            @foreach ($utilisateurs as $utilisateur)
                                <option value="{{ $utilisateur->id }}" @selected(request('user_id') == $utilisateur->id)>
                                    {{ $utilisateur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="action" class="block text-sm font-medium text-slate-700">Action</label>
                        <select name="action" id="action"
                                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="">Toutes</option>
                            @foreach ($actions as $action)
                                <option value="{{ $action }}" @selected(request('action') == $action)>
                                    {{ str_replace('_', ' ', $action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-slate-700">Du</label>
                        <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-slate-700">Au</label>
                        <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    </div>

                    <div class="sm:col-span-4 flex gap-3">
                        <button type="submit"
                                class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Filtrer
                        </button>
                        <a href="{{ route('activity-logs.index') }}"
                           class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tableau des logs --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100">
                <div class="p-6">

                    @if ($logs->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-slate-500">Aucune activité trouvée.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[820px] table-fixed divide-y divide-slate-100">
                                <colgroup><col class="w-[10rem]"><col class="w-[10.5rem]"><col><col class="w-[10.5rem]"></colgroup>
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Utilisateur</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Action</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Description</th>
                                        <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach ($logs as $log)
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="px-3 py-3 font-medium text-slate-800 text-left break-words">{{ $log->user->name ?? 'Utilisateur supprimé' }}</td>
                                            <td class="px-3 py-3 text-center">
                                                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                                    {{ str_replace('_', ' ', $log->action) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-slate-600 text-left break-words">{{ $log->description }}</td>
                                            <td class="px-3 py-3 text-sm text-slate-500 text-center whitespace-nowrap">{{ $log->created_at->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $logs->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

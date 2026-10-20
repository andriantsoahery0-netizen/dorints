<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
            Bonjour, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Bandeau de bienvenue --}}
            <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-800 rounded-2xl p-6 sm:p-8 shadow-lg overflow-hidden relative">
                <div class="absolute top-0 right-0 w-48 h-48 bg-amber-400/10 rounded-full -mr-16 -mt-16"></div>
                <div class="relative">
                    <p class="text-indigo-300 text-sm font-medium uppercase tracking-wider">
                        {{ auth()->user()->isAdmin() ? 'Espace administrateur' : 'Espace agent' }}
                    </p>
                    <h3 class="text-2xl font-bold text-white mt-1">
                        Bienvenue, {{ auth()->user()->name }}
                    </h3>
                    <p class="text-indigo-200 text-sm mt-2">
                        Voici un aperçu de l'activité du système D'ORINTS.
                    </p>
                </div>
            </div>

            {{-- Stats commandes --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('commandes.index', ['statut' => 'en_attente']) }}"
                   class="bg-white hover:shadow-md shadow-sm rounded-xl p-6 border border-gray-100 transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500">Commandes en attente</p>
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-indigo-700 transition">
                        {{ $stats['commandes_en_attente'] }}
                    </p>
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_cours']) }}"
                   class="bg-white hover:shadow-md shadow-sm rounded-xl p-6 border border-gray-100 transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500">Commandes en cours</p>
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-indigo-700 transition">
                        {{ $stats['commandes_en_cours'] }}
                    </p>
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'livree']) }}"
                   class="bg-white hover:shadow-md shadow-sm rounded-xl p-6 border border-gray-100 transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500">Commandes livrées</p>
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 mt-2 group-hover:text-indigo-700 transition">
                        {{ $stats['commandes_livrees'] }}
                    </p>
                </a>
            </div>

            {{-- Raccourcis rapides --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Accès rapide</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <a href="{{ route('clients.index') }}"
                       class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition">
                        <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Clients</span>
                    </a>
                    <a href="{{ route('commandes.index') }}"
                       class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition">
                        <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Commandes</span>
                    </a>
                    <a href="{{ route('commandes.create') }}"
                       class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100 transition">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm font-medium text-amber-800">Nouvelle commande</span>
                    </a>
                    <a href="{{ route('taux-changes.index') }}"
                       class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition">
                        <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Taux de change</span>
                    </a>
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                {{-- Raccourcis admin --}}
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Administration</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <a href="{{ route('users.index') }}"
                           class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Utilisateurs</span>
                        </a>
                        <a href="{{ route('rapports.index') }}"
                           class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Rapports</span>
                        </a>
                        <a href="{{ route('activity-logs.index') }}"
                           class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Journal d'activité</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

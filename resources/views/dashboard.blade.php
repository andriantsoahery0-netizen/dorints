<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bonjour, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stats commandes --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('commandes.index', ['statut' => 'en_attente']) }}"
                   class="bg-yellow-50 hover:bg-yellow-100 shadow-sm rounded-lg p-6 transition">
                    <p class="text-sm text-yellow-700">Commandes en attente</p>
                    <p class="text-3xl font-bold text-yellow-800">{{ $stats['commandes_en_attente'] }}</p>
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'en_cours']) }}"
                   class="bg-blue-50 hover:bg-blue-100 shadow-sm rounded-lg p-6 transition">
                    <p class="text-sm text-blue-700">Commandes en cours</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $stats['commandes_en_cours'] }}</p>
                </a>
                <a href="{{ route('commandes.index', ['statut' => 'livree']) }}"
                   class="bg-green-50 hover:bg-green-100 shadow-sm rounded-lg p-6 transition">
                    <p class="text-sm text-green-700">Commandes livrées</p>
                    <p class="text-3xl font-bold text-green-800">{{ $stats['commandes_livrees'] }}</p>
                </a>
            </div>

            {{-- Raccourcis rapides --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Accès rapide</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('clients.index') }}"
                       class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-sm font-medium text-gray-700">Clients</span>
                    </a>
                    <a href="{{ route('commandes.index') }}"
                       class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-sm font-medium text-gray-700">Commandes</span>
                    </a>
                    <a href="{{ route('commandes.create') }}"
                       class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-sm font-medium text-gray-700">+ Nouvelle commande</span>
                    </a>
                    <a href="{{ route('taux-changes.index') }}"
                       class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <span class="text-sm font-medium text-gray-700">Taux de change</span>
                    </a>
                </div>
            </div>

            @if (auth()->user()->isAdmin())
                {{-- Raccourcis admin --}}
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Administration</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <a href="{{ route('users.index') }}"
                           class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <span class="text-sm font-medium text-gray-700">Utilisateurs</span>
                        </a>
                        <a href="{{ route('rapports.index') }}"
                           class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <span class="text-sm font-medium text-gray-700">Rapports</span>
                        </a>
                        <a href="{{ route('activity-logs.index') }}"
                           class="flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <span class="text-sm font-medium text-gray-700">Journal d'activité</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
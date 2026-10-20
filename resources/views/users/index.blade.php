<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-indigo-900 leading-tight">
                Gestion des Utilisateurs
            </h2>
            <a href="{{ route('users.create') }}"
               class="flex items-center gap-1.5 bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel utilisateur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6">

                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rôle</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('users.show', $user) }}" class="text-indigo-700 hover:underline font-medium">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-purple-100 text-purple-800' => $user->role === 'admin',
                                            'bg-gray-100 text-gray-700' => $user->role === 'agent',
                                        ])>
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-800' => $user->actif,
                                            'bg-red-100 text-red-800' => !$user->actif,
                                        ])>
                                            {{ $user->actif ? 'Actif' : 'Désactivé' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-3">
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('users.toggle-actif', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" @class([
                                                    'font-medium text-sm hover:underline',
                                                    'text-amber-600' => $user->actif,
                                                    'text-green-600' => !$user->actif,
                                                ])>
                                                    {{ $user->actif ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm">(vous)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

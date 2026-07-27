<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Utilisateurs
            </h2>
            <a href="{{ route('users.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm">
                + Nouvel utilisateur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('users.show', $user) }}" class="text-indigo-700 hover:underline font-medium">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-purple-100 text-purple-800' => $user->role === 'admin',
                                            'bg-gray-100 text-gray-800' => $user->role === 'agent',
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
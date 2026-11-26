<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                    Gestion des Utilisateurs
                </h2>
                <a href="{{ route('users.create') }}"
                   class="flex items-center gap-1.5 bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvel utilisateur
                </a>
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">

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

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100">
                <div class="p-6">

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[880px] table-fixed divide-y divide-slate-100">
                            <colgroup><col><col><col><col class="w-[6.5rem]"><col class="w-[7.5rem]"><col class="w-[12rem]"></colgroup>
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Nom</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Prénom</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">Email</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Rôle</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Statut</th>
                                    <th class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-3 py-3 text-left break-words hyphens-auto">
                                            <a href="{{ route('users.show', $user) }}" class="text-blue-700 hover:underline font-medium">{{ $user->nom }}</a>
                                        </td>
                                        <td class="px-3 py-3 text-slate-600 text-left break-words hyphens-auto">{{ $user->prenom }}</td>
                                        <td class="px-3 py-3 text-slate-600 text-left break-all">{{ $user->email }}</td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            <span @class([
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-blue-800 text-white' => $user->role === 'admin',
                                                'bg-slate-100 text-slate-700' => $user->role === 'agent',
                                            ])>
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            <span @class([
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-green-100 text-green-800' => $user->actif,
                                                'bg-red-100 text-red-800' => !$user->actif,
                                            ])>
                                                {{ $user->actif ? 'Actif' : 'Désactivé' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 space-x-3 text-right whitespace-nowrap">
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('users.toggle-actif', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" @class([
                                                        'font-medium text-sm hover:underline',
                                                        'text-red-600' => $user->actif,
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
                                                <span class="text-slate-400 text-sm">(vous)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

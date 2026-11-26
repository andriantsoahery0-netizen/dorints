<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        @php
            $tauxDuJour = \App\Models\TauxChange::actuel();
            $nonLues = auth()->user()->unreadNotifications->count();
        @endphp

        <div x-data="{ menu: false }" class="min-h-screen lg:flex">
            @include('layouts.navigation')

            <div class="flex min-w-0 flex-1 flex-col">
                {{-- Barre du haut --}}
                <div class="sticky top-0 z-30 flex items-center gap-3 border-b border-slate-200 bg-white/95 px-4 py-3 backdrop-blur sm:px-6 lg:px-8">
                    <button type="button" @click="menu = true" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" aria-label="Ouvrir le menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>

                    <form method="GET" action="{{ route('recherche') }}" class="min-w-0 flex-1 sm:max-w-md" role="search">
                        <label class="flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-500 focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path stroke-linecap="round" d="M21 21l-4.3-4.3" /></svg>
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher client, commande, facture…" aria-label="Recherche"
                                   class="w-full border-0 bg-transparent p-0 text-sm text-slate-900 placeholder-slate-500 focus:ring-0">
                        </label>
                    </form>
                    <div class="hidden flex-1 sm:block"></div>

                    @if ($tauxDuJour)
                        <a href="{{ route('taux-changes.index') }}" class="hidden rounded-lg bg-blue-50 px-3 py-1.5 text-sm text-blue-900 hover:bg-blue-100 sm:block">
                            1 € = <span class="font-semibold">{{ number_format($tauxDuJour->taux_eur_vers_mga, 0, ',', ' ') }} Ar</span>
                        </a>
                    @endif

                    {{-- Notifications --}}
                    <x-dropdown align="right" width="w-80">
                        <x-slot name="trigger">
                            <button type="button" class="relative rounded-lg p-2 text-slate-600 hover:bg-slate-100" aria-label="Notifications">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3C7.7 6.2 6 8.4 6 11v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if ($nonLues > 0)
                                    <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white">{{ $nonLues }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="max-h-96 overflow-y-auto">
                                @forelse (auth()->user()->notifications->take(8) as $notification)
                                    <a href="{{ route('notifications.ouvrir', $notification->id) }}"
                                       class="flex gap-3 border-b border-slate-100 px-4 py-3 text-sm transition hover:bg-slate-50 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $notification->read_at ? 'bg-transparent' : 'bg-blue-600' }}"></span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-slate-700">{{ \App\Support\Libelles::notification($notification) }}</span>
                                            <span class="mt-1 block text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</span>
                                        </span>
                                    </a>
                                @empty
                                    <div class="px-4 py-3 text-sm text-slate-500">Aucune notification.</div>
                                @endforelse
                            </div>
                            <div class="flex border-t border-slate-100 text-sm">
                                <a href="{{ route('notifications.index') }}" class="flex-1 px-4 py-2 text-center text-blue-700 hover:bg-slate-50">Voir tout</a>
                                @if ($nonLues > 0)
                                    <form method="POST" action="{{ route('notifications.markAllRead') }}" class="flex-1 border-l border-slate-100">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 text-center text-blue-700 hover:bg-slate-50">Tout marquer comme lu</button>
                                    </form>
                                @endif
                            </div>
                        </x-slot>
                    </x-dropdown>

                    {{-- Compte --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-800 text-xs font-bold text-white">
                                    {{ mb_strtoupper(mb_substr(Auth::user()->prenom ?: Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden text-left leading-tight md:block">
                                    <span class="block">{{ Auth::user()->name }}</span>
                                    <span class="block text-xs font-normal text-slate-500">{{ Auth::user()->isAdmin() ? 'Administrateur' : 'Agent' }}</span>
                                </span>
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Mon profil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Se déconnecter
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- En-tête de la page --}}
                @isset($header)
                    <header class="pt-6">
                        {{ $header }}
                    </header>
                @endisset

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @include('partials.oeil-mot-de-passe')
    </body>
</html>

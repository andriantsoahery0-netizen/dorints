<nav x-data="{ open: false }" class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-b border-indigo-900/50 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 shadow-sm">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 4v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <span class="block text-lg font-extrabold tracking-tight text-white">
                                D'ORINTS
                            </span>
                            <span class="block text-[10px] font-medium uppercase tracking-wider text-indigo-300">
                                Gestion &amp; Transfert
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:ms-10 sm:flex sm:items-center">
                    <a href="{{ route('dashboard') }}"
                       @class([
                           'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                           'bg-amber-400/10 text-amber-300 ring-1 ring-amber-400/30' => request()->routeIs('dashboard'),
                           'text-indigo-200 hover:bg-white/5 hover:text-white' => !request()->routeIs('dashboard'),
                       ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('clients.index') }}"
                       @class([
                           'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                           'bg-amber-400/10 text-amber-300 ring-1 ring-amber-400/30' => request()->routeIs('clients.*'),
                           'text-indigo-200 hover:bg-white/5 hover:text-white' => !request()->routeIs('clients.*'),
                       ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 3c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                        Clients
                    </a>
                    <a href="{{ route('commandes.index') }}"
                       @class([
                           'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                           'bg-amber-400/10 text-amber-300 ring-1 ring-amber-400/30' => request()->routeIs('commandes.*'),
                           'text-indigo-200 hover:bg-white/5 hover:text-white' => !request()->routeIs('commandes.*'),
                       ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Commandes
                    </a>
                    <a href="{{ route('taux-changes.index') }}"
                       @class([
                           'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                           'bg-amber-400/10 text-amber-300 ring-1 ring-amber-400/30' => request()->routeIs('taux-changes.*'),
                           'text-indigo-200 hover:bg-white/5 hover:text-white' => !request()->routeIs('taux-changes.*'),
                       ])>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Taux de change
                    </a>

                    @if (auth()->user()->isAdmin())
                        <div class="w-px h-6 bg-indigo-800 mx-2"></div>

                        <a href="{{ route('users.index') }}"
                           @class([
                               'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                               'bg-amber-400/10 text-amber-300 ring-1 ring-amber-400/30' => request()->routeIs('users.*'),
                               'text-indigo-200 hover:bg-white/5 hover:text-white' => !request()->routeIs('users.*'),
                           ])>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Utilisateurs
                        </a>
                        <a href="{{ route('rapports.index') }}"
                           @class([
                               'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                               'bg-amber-400/10 text-amber-300 ring-1 ring-amber-400/30' => request()->routeIs('rapports.*'),
                               'text-indigo-200 hover:bg-white/5 hover:text-white' => !request()->routeIs('rapports.*'),
                           ])>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Rapports
                        </a>
                        <a href="{{ route('activity-logs.index') }}"
                           @class([
                               'flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition',
                               'bg-amber-400/10 text-amber-300 ring-1 ring-amber-400/30' => request()->routeIs('activity-logs.*'),
                               'text-indigo-200 hover:bg-white/5 hover:text-white' => !request()->routeIs('activity-logs.*'),
                           ])>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Journal
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <!-- Notifications -->
                <x-dropdown align="right" width="80">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center p-2 text-indigo-200 hover:text-white rounded-lg hover:bg-white/5 transition focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @php $nonLues = auth()->user()->unreadNotifications->count(); @endphp
                            @if ($nonLues > 0)
                                <span class="absolute top-1 right-1 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold leading-none text-slate-900 bg-amber-400 rounded-full">
                                    {{ $nonLues }}
                                </span>
                            @endif
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="max-h-96 overflow-y-auto">
                            @forelse (auth()->user()->notifications->take(10) as $notification)
                                <div class="px-4 py-3 text-sm border-b border-gray-100 {{ $notification->read_at ? 'bg-white' : 'bg-amber-50' }}">
                                    <p class="text-gray-700">{{ $notification->data['message'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500">Aucune notification.</div>
                            @endforelse
                        </div>
                        @if ($nonLues > 0)
                            <form method="POST" action="{{ route('notifications.markAllRead') }}" class="border-t border-gray-100">
                                @csrf
                                <button type="submit" class="w-full text-center px-4 py-2 text-sm text-indigo-600 hover:bg-gray-50">
                                    Tout marquer comme lu
                                </button>
                            </form>
                        @endif
                    </x-slot>
                </x-dropdown>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-indigo-200 hover:text-white hover:bg-white/5 transition focus:outline-none">
                            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-indigo-700 text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden lg:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-white/5 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-cloak :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900/95 border-t border-indigo-900/50">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('dashboard') }}" @class(['block px-3 py-2 rounded-lg text-sm font-medium', 'bg-amber-400/10 text-amber-300' => request()->routeIs('dashboard'), 'text-indigo-200' => !request()->routeIs('dashboard')])>Dashboard</a>
            <a href="{{ route('clients.index') }}" @class(['block px-3 py-2 rounded-lg text-sm font-medium', 'bg-amber-400/10 text-amber-300' => request()->routeIs('clients.*'), 'text-indigo-200' => !request()->routeIs('clients.*')])>Clients</a>
            <a href="{{ route('commandes.index') }}" @class(['block px-3 py-2 rounded-lg text-sm font-medium', 'bg-amber-400/10 text-amber-300' => request()->routeIs('commandes.*'), 'text-indigo-200' => !request()->routeIs('commandes.*')])>Commandes</a>
            <a href="{{ route('taux-changes.index') }}" @class(['block px-3 py-2 rounded-lg text-sm font-medium', 'bg-amber-400/10 text-amber-300' => request()->routeIs('taux-changes.*'), 'text-indigo-200' => !request()->routeIs('taux-changes.*')])>Taux de change</a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" @class(['block px-3 py-2 rounded-lg text-sm font-medium', 'bg-amber-400/10 text-amber-300' => request()->routeIs('users.*'), 'text-indigo-200' => !request()->routeIs('users.*')])>Utilisateurs</a>
                <a href="{{ route('rapports.index') }}" @class(['block px-3 py-2 rounded-lg text-sm font-medium', 'bg-amber-400/10 text-amber-300' => request()->routeIs('rapports.*'), 'text-indigo-200' => !request()->routeIs('rapports.*')])>Rapports</a>
                <a href="{{ route('activity-logs.index') }}" @class(['block px-3 py-2 rounded-lg text-sm font-medium', 'bg-amber-400/10 text-amber-300' => request()->routeIs('activity-logs.*'), 'text-indigo-200' => !request()->routeIs('activity-logs.*')])>Journal</a>
            @endif
        </div>

        <div class="pt-4 pb-3 border-t border-indigo-900/50">
            <div class="px-4 flex items-center gap-3">
                <span class="flex items-center justify-center w-9 h-9 rounded-full bg-indigo-700 text-white text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div>
                    <div class="font-medium text-sm text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-indigo-300">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-indigo-200">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
{{-- Menu latéral : fixe sur ordinateur, tiroir sur téléphone (variable « menu » de app.blade.php) --}}
@php
    // Compteurs affichés dans le menu : ce qui attend une action
    $aValider = \App\Models\Commande::where('statut', 'en_attente')->count();
    $nonReglees = \App\Models\Facture::nonReglees()->count();

    $liens = [
        ['route' => 'dashboard', 'actif' => 'dashboard', 'libelle' => 'Tableau de bord',
         'icone' => 'M3 3h7v9H3zM14 3h7v5h-7zM14 12h7v9h-7zM3 16h7v5H3z'],
        ['route' => 'clients.index', 'actif' => 'clients.*', 'libelle' => 'Clients',
         'icone' => 'M9 11a4 4 0 100-8 4 4 0 000 8zM2 21c0-4 3-6 7-6s7 2 7 6M17 11a3 3 0 100-6M22 21c0-3-2-5-5-5.5'],
        ['route' => 'commandes.index', 'actif' => 'commandes.*', 'libelle' => 'Commandes', 'compteur' => $aValider,
         'icone' => 'M6 2h12v20l-6-3-6 3z'],
        ['route' => 'factures.index', 'actif' => 'factures.*', 'libelle' => 'Factures', 'compteur' => $nonReglees,
         'icone' => 'M4 3h16v18H4zM8 8h8M8 12h8M8 16h5'],
        ['route' => 'taux-changes.index', 'actif' => 'taux-changes.*', 'libelle' => 'Taux de change',
         'icone' => 'M7 7h13l-4-4M17 17H4l4 4'],
    ];
    $liensAdmin = [
        ['route' => 'rapports.index', 'actif' => 'rapports.*', 'libelle' => 'Rapports',
         'icone' => 'M3 17l6-6 4 4 8-8M15 7h6v6'],
        ['route' => 'users.index', 'actif' => 'users.*', 'libelle' => 'Utilisateurs',
         'icone' => 'M12 11a4 4 0 100-8 4 4 0 000 8zM5 21a7 7 0 0114 0'],
        ['route' => 'activity-logs.index', 'actif' => 'activity-logs.*', 'libelle' => 'Journal',
         'icone' => 'M12 8v4l3 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['route' => 'sauvegarde.index', 'actif' => 'sauvegarde.*', 'libelle' => 'Sauvegarde',
         'icone' => 'M12 4v12m0 0l-4-4m4 4l4-4M4 20h16'],
    ];
@endphp

{{-- Fond sombre derrière le menu ouvert sur téléphone --}}
<div x-show="menu" x-transition.opacity @click="menu = false" class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden" style="display: none"></div>

<aside :class="menu ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transform bg-[#c4e3f7] text-slate-900 border-r border-[#a6d0ee] transition-transform duration-200 lg:static lg:min-h-screen lg:w-60 lg:shrink-0 lg:translate-x-0">
  <div class="flex h-full flex-col lg:sticky lg:top-0 lg:h-screen">

    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-5 py-5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 shadow-sm">
            <svg class="h-5 w-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 4v12m0 0l4-4m-4 4l-4-4" /></svg>
        </span>
        <span class="leading-tight">
            <span class="block font-display text-lg font-bold text-slate-900">D'ORINTS</span>
            <span class="block text-[11px] uppercase tracking-wider text-blue-800">Gestion &amp; transfert</span>
        </span>
    </a>

    <nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="Menu principal">
        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-blue-700">Activité</p>
        <div class="space-y-1">
            @foreach ($liens as $lien)
                <a href="{{ route($lien['route']) }}"
                   @class([
                       'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
                       'bg-white text-blue-800 shadow-sm' => request()->routeIs($lien['actif']),
                       'hover:bg-white/60 hover:text-slate-900' => ! request()->routeIs($lien['actif']),
                   ])>
                    <svg class="h-5 w-5 {{ request()->routeIs($lien['actif']) ? 'text-blue-800' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $lien['icone'] }}" />
                    </svg>
                    <span class="flex-1">{{ $lien['libelle'] }}</span>
                    @if (! empty($lien['compteur']))
                        <span class="rounded-full bg-blue-800 px-2 py-0.5 text-xs font-semibold text-white" title="{{ $lien['route'] === 'commandes.index' ? 'Commandes à valider' : 'Factures non réglées' }}">{{ $lien['compteur'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        @if (auth()->user()->isAdmin())
            <p class="mt-6 mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-blue-700">Administration</p>
            <div class="space-y-1">
                @foreach ($liensAdmin as $lien)
                    <a href="{{ route($lien['route']) }}"
                       @class([
                           'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition',
                           'bg-white text-blue-800 shadow-sm' => request()->routeIs($lien['actif']),
                           'hover:bg-white/60 hover:text-slate-900' => ! request()->routeIs($lien['actif']),
                       ])>
                        <svg class="h-5 w-5 {{ request()->routeIs($lien['actif']) ? 'text-blue-800' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $lien['icone'] }}" />
                        </svg>
                        {{ $lien['libelle'] }}
                    </a>
                @endforeach
            </div>
        @endif
    </nav>

    <div class="border-t border-[#a6d0ee] px-5 py-4 text-xs text-blue-900">
        Connecté en tant que <span class="font-semibold text-slate-800">{{ auth()->user()->isAdmin() ? 'administrateur' : 'agent' }}</span>
    </div>
  </div>
</aside>

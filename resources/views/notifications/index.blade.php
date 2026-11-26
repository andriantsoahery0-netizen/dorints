<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">Notifications</h2>
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                            Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl px-4 sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                @forelse ($notifications as $notification)
                    <a href="{{ route('notifications.ouvrir', $notification->id) }}"
                       class="flex items-start gap-3 border-b border-slate-100 px-5 py-4 transition last:border-0 hover:bg-slate-50 {{ $notification->read_at ? '' : 'bg-blue-50/60' }}">
                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full {{ $notification->read_at ? 'bg-slate-300' : 'bg-blue-600' }}"></span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm {{ $notification->read_at ? 'text-slate-700' : 'font-semibold text-slate-900' }}">{{ \App\Support\Libelles::notification($notification) }}</span>
                            <span class="mt-1 block text-xs text-slate-500">{{ $notification->created_at->format('d/m/Y à H:i') }} · {{ $notification->created_at->diffForHumans() }}</span>
                        </span>
                        <span class="self-center text-sm text-blue-700">Voir →</span>
                    </a>
                @empty
                    <p class="px-5 py-10 text-center text-sm text-slate-500">Aucune notification.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>

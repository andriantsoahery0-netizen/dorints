<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Mon profil
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Message de confirmation, visible en haut de la page après l'enregistrement --}}
            @php
                $messageProfil = match (session('status')) {
                    'password-updated' => 'Votre mot de passe a été mis à jour.',
                    'profile-updated' => 'Vos informations ont été mises à jour.',
                    default => null,
                };
            @endphp
            @if ($messageProfil)
                <div role="status" style="display:flex;align-items:center;gap:0.5rem;border:1px solid #bbf7d0;background:#f0fdf4;color:#166534;border-radius:0.5rem;padding:0.75rem 1rem;font-size:0.9rem;font-weight:600;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $messageProfil }}
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

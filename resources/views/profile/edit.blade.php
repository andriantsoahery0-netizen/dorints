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

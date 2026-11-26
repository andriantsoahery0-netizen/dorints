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
    <body class="font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-slate-100 px-4 py-10">
            <a href="/" class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 shadow-sm">
                    <svg class="h-7 w-7 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 4v12m0 0l4-4m-4 4l-4-4" /></svg>
                </span>
                <span class="leading-tight">
                    <span class="block font-display text-2xl font-bold text-slate-900">D'ORINTS</span>
                    <span class="block text-xs uppercase tracking-wider text-slate-500">Gestion &amp; transfert</span>
                </span>
            </a>

            <div class="mt-8 w-full max-w-md rounded-xl bg-white px-6 py-6 shadow-xl sm:px-8">
                {{ $slot }}
            </div>
        </div>
        @include('partials.oeil-mot-de-passe')
    </body>
</html>

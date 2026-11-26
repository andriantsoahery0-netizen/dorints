<x-app-layout>
    <x-slot name="header">
        <div class="max-w-3xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">Sauvegarde des données</h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-3">
                    <h3 class="font-display text-lg font-bold text-slate-900">Télécharger une copie complète</h3>
                </div>
                <div class="space-y-4 p-5 text-sm text-slate-700">
                    <p>La sauvegarde réunit toutes les données de l'application dans un fichier ZIP. Chaque partie est un fichier CSV qui s'ouvre dans Excel.
                       Conservez-la en lieu sûr (clé USB, disque externe, Google Drive).</p>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[480px] table-fixed text-sm">
                            <colgroup><col class="w-[40%]"><col class="w-[40%]"><col class="w-[20%]"></colgroup>
                            <thead>
                                <tr class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                                    <th class="px-4 py-2 font-semibold text-left">Contenu</th>
                                    <th class="px-4 py-2 font-semibold text-left">Fichier</th>
                                    <th class="px-4 py-2 font-semibold text-right">Lignes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($tables as $table)
                                    <tr>
                                        <td class="px-4 py-2 text-left">{{ $table['nom'] }}</td>
                                        <td class="px-4 py-2 text-slate-500 text-left break-all">{{ $table['fichier'] }}</td>
                                        <td class="px-4 py-2 text-right whitespace-nowrap">{{ $table['lignes'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="text-xs text-slate-500">Les mots de passe des utilisateurs ne sont jamais inclus.</p>

                    <a href="{{ route('sauvegarde.telecharger') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-900">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" /></svg>
                        Télécharger la sauvegarde
                    </a>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold text-slate-900 leading-tight">
                Nouvel utilisateur
            </h2>
    
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-100 p-6">

                <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="nom" class="block text-sm font-medium text-slate-700">Nom</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                               class="uppercase mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('nom')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-slate-700">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('prenom')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
                        <div class="mt-1 flex gap-2">
                            <input type="password" name="password" id="password"
                                   class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <button type="button" onclick="genererMotDePasse()"
                                    class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm"
                                    style="white-space:nowrap">
                                Générer
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Minimum 8 caractères. Tapez-le vous-même ou cliquez sur « Générer » : il remplit aussi la confirmation et sera imprimé sur le ticket.</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmation du mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700">Rôle</label>
                        <select name="role" id="role"
                                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                            <option value="agent" @selected(old('role') === 'agent')>Agent</option>
                            <option value="admin" @selected(old('role') === 'admin')>Administrateur</option>
                        </select>
                        @error('role')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('users.index') }}"
                           class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Créer l'utilisateur
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        // Mot de passe de 10 caractères, sans caractères ambigus (0/O, 1/l/I)
        function genererMotDePasse() {
            const caracteres = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
            const aleatoire = new Uint32Array(10);
            crypto.getRandomValues(aleatoire);
            const motDePasse = Array.from(aleatoire, (n) => caracteres[n % caracteres.length]).join('');
            document.getElementById('password').value = motDePasse;
            document.getElementById('password_confirmation').value = motDePasse;
            // Les deux champs passent en clair (icône œil barrée)
            ['password', 'password_confirmation'].forEach((id) => window.oeilAfficher(document.getElementById(id), true));
        }

    </script>
</x-app-layout>

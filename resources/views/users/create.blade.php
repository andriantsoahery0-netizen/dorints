<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nouvel utilisateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">

                <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                        @error('nom')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                        @error('prenom')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" name="password" id="password"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères.</p>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                        <select name="role" id="role"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                            <option value="agent" @selected(old('role') === 'agent')>Agent</option>
                            <option value="admin" @selected(old('role') === 'admin')>Administrateur</option>
                        </select>
                        @error('role')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('users.index') }}"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm transition">
                            Annuler
                        </a>
                        <button type="submit"
                                class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                            Créer l'utilisateur
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

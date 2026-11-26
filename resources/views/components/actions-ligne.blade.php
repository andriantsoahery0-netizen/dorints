@props(['voir', 'modifier', 'supprimer', 'confirmation' => 'Supprimer cet élément ?'])
{{-- Liens Voir / Modifier / Supprimer d'une ligne de tableau --}}
<div class="inline-flex items-center gap-3">
    <a href="{{ $voir }}" class="text-blue-700 hover:text-blue-900 font-medium text-sm">Voir</a>
    <a href="{{ $modifier }}" class="text-slate-600 hover:text-slate-900 font-medium text-sm">Modifier</a>
    <form action="{{ $supprimer }}" method="POST" class="inline" onsubmit="return confirm(@js($confirmation));">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Supprimer</button>
    </form>
</div>

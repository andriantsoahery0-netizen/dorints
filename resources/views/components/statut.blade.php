@props(['statut'])
{{-- Statut d'une commande : un seul style neutre, le libellé suffit --}}
<span {{ $attributes->merge(['class' => 'inline-block whitespace-nowrap rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700']) }}>
    {{ ['en_attente' => 'En attente', 'validee' => 'Validée', 'en_cours' => 'En cours', 'livree' => 'Livrée'][$statut] ?? $statut }}
</span>

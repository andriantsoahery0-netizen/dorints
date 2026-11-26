@props(['client'])
{{-- Nom (toujours en majuscules) puis prénom ; un nom long passe à la ligne entre les mots --}}
<span class="break-words"><span class="font-medium text-slate-800">{{ $client->nom }}</span> <span class="text-slate-600">{{ $client->prenom }}</span></span>

@extends('emails.layout')
@php
    $client = $commande->client;
    $reste = $commande->facture ? $commande->resteAPayer() : 0;
@endphp
@section('contenu')
    <p>Bonjour {{ $client->prenom }} {{ $client->nom }},</p>

    @if ($commande->statut === 'livree')
        <p>Bonne nouvelle : votre commande <strong>{{ $commande->numero_commande }}</strong> est <strong>livrée</strong>.</p>
        <p>Merci de votre confiance. N'hésitez pas à nous contacter pour toute nouvelle commande.</p>
    @else
        <p>Votre commande <strong>{{ $commande->numero_commande }}</strong> est maintenant <strong>en cours</strong> :
           nous procédons à l'achat de vos produits en Europe.</p>
        <p>Nous vous préviendrons dès qu'elle sera livrée.</p>
    @endif

    <p style="color:#475569;font-size:14px">Produits : {{ $commande->lignes->map(fn ($l) => $l->designation . ($l->quantite > 1 ? ' ×' . $l->quantite : ''))->implode(', ') }}</p>

    @if ($commande->facture)
        <p>Vous trouverez votre facture <strong>{{ $commande->facture->numero_facture }}</strong> à jour en pièce jointe (PDF).</p>
    @endif

    @if ($reste > 0)
        <p style="background:#fef2f2;color:#991b1b;border-radius:8px;padding:10px 14px">
            Il reste <strong>{{ number_format($reste, 0, ',', ' ') }} Ar</strong> à régler sur la facture {{ $commande->facture->numero_facture }}.
        </p>
    @endif
@endsection

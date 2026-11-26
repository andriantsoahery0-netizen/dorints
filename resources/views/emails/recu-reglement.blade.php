@extends('emails.layout')
@php
    $commande = $reglement->commande;
    $facture = $commande->facture;
    $client = $commande->client;
    $ar = fn ($v) => number_format($v, 0, ',', ' ') . ' Ar';
    $reste = $commande->resteAPayer();
@endphp
@section('contenu')
    <p>Bonjour {{ $client->prenom }} {{ $client->nom }},</p>

    <p>Nous avons bien reçu votre paiement de <strong>{{ $ar($reglement->montant_envoye) }}</strong>
       ({{ \App\Models\Transfert::MODES[$reglement->mode_paiement] ?? 'paiement' }}, le {{ $reglement->created_at->format('d/m/Y') }}).</p>

    <table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px">
        <tr><td style="padding:6px 0;color:#475569">Facture</td><td style="padding:6px 0;text-align:right">{{ $facture->numero_facture }} (commande {{ $commande->numero_commande }})</td></tr>
        <tr><td style="padding:6px 0;color:#475569">Montant de la facture</td><td style="padding:6px 0;text-align:right">{{ $ar($facture->montant) }}</td></tr>
        <tr><td style="padding:6px 0;color:#475569">Total déjà réglé</td><td style="padding:6px 0;text-align:right">{{ $ar($commande->montantPaye()) }}</td></tr>
        <tr><td style="padding:8px 0;border-top:1px solid #e2e8f0;font-weight:bold">Reste à payer</td>
            <td style="padding:8px 0;border-top:1px solid #e2e8f0;text-align:right;font-weight:bold;color:{{ $reste > 0 ? '#b91c1c' : '#15803d' }}">{{ $ar($reste) }}</td></tr>
    </table>

    @if ($reste <= 0)
        <p style="background:#f0fdf4;color:#166534;border-radius:8px;padding:10px 14px">Votre facture est entièrement réglée. Merci !</p>
    @endif

    <p>Vous trouverez votre facture <strong>{{ $facture->numero_facture }}</strong> à jour en pièce jointe (PDF), avec ce règlement.</p>

    <p style="color:#475569;font-size:13px">Conservez cet e-mail comme reçu de paiement.</p>
@endsection

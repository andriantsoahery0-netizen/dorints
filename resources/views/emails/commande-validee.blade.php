@extends('emails.layout')
@php
    $client = $commande->client;
    $ar = fn ($v) => number_format($v, 0, ',', ' ') . ' Ar';
@endphp
@section('contenu')
    <p>Bonjour {{ $client->prenom }} {{ $client->nom }},</p>

    <p>Votre commande <strong>{{ $commande->numero_commande }}</strong> est validée. Voici son récapitulatif :</p>

    <table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px">
        <tr style="color:#64748b;font-size:12px;text-transform:uppercase">
            <td style="padding:6px 0;border-bottom:2px solid #e2e8f0">Produit</td>
            <td style="padding:6px 0;border-bottom:2px solid #e2e8f0;text-align:right">Qté</td>
            <td style="padding:6px 0;border-bottom:2px solid #e2e8f0;text-align:right">Montant</td>
        </tr>
        @foreach ($commande->lignes as $ligne)
            <tr>
                <td style="padding:7px 0;border-bottom:1px solid #f1f5f9">{{ $ligne->designation }}</td>
                <td style="padding:7px 0;border-bottom:1px solid #f1f5f9;text-align:right">{{ $ligne->quantite }}</td>
                <td style="padding:7px 0;border-bottom:1px solid #f1f5f9;text-align:right">{{ $ar($ligne->sousTotal()) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="padding:8px 0;font-weight:bold">Total à payer</td>
            <td style="padding:8px 0;text-align:right;font-weight:bold">{{ $ar($commande->montant_total) }}</td>
        </tr>
    </table>

    <p>Vous trouverez la facture <strong>{{ $commande->facture->numero_facture }}</strong> en pièce jointe (PDF).</p>

    <p>Vous pouvez régler en espèces à notre agence, ou par MVola, Orange Money, Airtel Money ou virement bancaire.
       Nous lancerons l'achat de vos produits dès réception de votre règlement.</p>
@endsection

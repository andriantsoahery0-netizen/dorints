{{-- Facture au format PDF (dompdf) : mise en page en tableaux et styles simples --}}
@php
    $commande = $facture->commande;
    $client = $commande->client;
    $sp = $commande->statutPaiement();
    $reglements = $commande->transferts->where('devise_source', 'MGA')->sortBy('created_at');
    $ar = fn ($v) => number_format($v, 0, ',', ' ') . ' Ar';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        @page { margin: 36px 40px; }
        body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; }
        .gris { color: #475569; }
        .petit { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }
        .lignes th { text-align: left; font-size: 9px; text-transform: uppercase; color: #64748b; border-bottom: 2px solid #cbd5e1; padding: 6px 4px; }
        .lignes td { border-bottom: 1px solid #e2e8f0; padding: 7px 4px; }
        .droite, .lignes .droite { text-align: right; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td style="vertical-align: top">
                <div style="font-size: 20px; font-weight: bold; color: #1e1b4b">D'ORINTS</div>
                <div class="gris">Gestion des commandes et transferts</div>
                <div class="gris">Antananarivo, Madagascar</div>
            </td>
            <td class="droite" style="vertical-align: top">
                <div style="font-size: 22px; font-weight: bold; letter-spacing: 1px">FACTURE</div>
                <div class="gris">N° <strong style="color: #0f172a">{{ $facture->numero_facture }}</strong></div>
                <div class="gris">Date : {{ $facture->created_at->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 1px solid #cbd5e1; margin: 16px 0">

    <table>
        <tr>
            <td style="vertical-align: top; width: 50%">
                <div class="petit">Facturé à</div>
                <div style="font-weight: bold; margin-top: 3px">{{ $client->nom }} {{ $client->prenom }}</div>
                <div class="gris">{{ $client->adresse }}</div>
                <div class="gris">{{ $client->telephone }}</div>
                @if ($client->email)
                    <div class="gris">{{ $client->email }}</div>
                @endif
            </td>
            <td class="droite" style="vertical-align: top">
                <div class="petit">Commande</div>
                <div style="font-weight: bold; margin-top: 3px">{{ $commande->numero_commande }}</div>
                <div class="gris">Passée le {{ $commande->created_at->format('d/m/Y') }}</div>
                <div class="gris">Validée par {{ $facture->agent->name }}</div>
            </td>
        </tr>
    </table>

    <table class="lignes" style="margin-top: 20px">
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="droite">Quantité</th>
                <th class="droite">Prix unitaire</th>
                <th class="droite">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commande->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->designation }}</td>
                    <td class="droite">{{ $ligne->quantite }}</td>
                    <td class="droite">{{ $ar($ligne->prix_unitaire) }}</td>
                    <td class="droite">{{ $ar($ligne->sousTotal()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="margin-top: 14px">
        <tr>
            <td style="width: 55%"></td>
            <td>
                <table>
                    <tr><td style="padding: 4px 0; font-weight: bold; font-size: 13px; border-bottom: 1px solid #cbd5e1">Total à payer</td>
                        <td class="droite" style="padding: 4px 0; font-weight: bold; font-size: 13px; border-bottom: 1px solid #cbd5e1">{{ $ar($facture->montant) }}</td></tr>
                    <tr><td class="gris" style="padding: 4px 0">Déjà payé</td><td class="droite gris" style="padding: 4px 0">{{ $ar($commande->montantPaye()) }}</td></tr>
                    <tr><td style="padding: 4px 0; font-weight: bold">Reste à payer</td><td class="droite" style="padding: 4px 0; font-weight: bold; color: {{ $commande->resteAPayer() > 0 ? '#b91c1c' : '#0f172a' }}">{{ $ar($commande->resteAPayer()) }}</td></tr>
                    <tr><td colspan="2" class="droite" style="padding-top: 6px; font-weight: bold; color: {{ $sp === 'paye' ? '#15803d' : '#b91c1c' }}">
                        {{ ['paye' => 'Réglée', 'partiel' => 'Partiellement réglée', 'non_paye' => 'Non réglée'][$sp] }}
                    </td></tr>
                </table>
            </td>
        </tr>
    </table>

    @if ($reglements->isNotEmpty())
        <div class="petit" style="margin-top: 24px">Règlements reçus</div>
        <table class="lignes" style="margin-top: 4px">
            <thead><tr><th>Date</th><th>Mode de paiement</th><th class="droite">Montant</th></tr></thead>
            <tbody>
                @foreach ($reglements as $reglement)
                    <tr>
                        <td>{{ $reglement->created_at->format('d/m/Y') }}</td>
                        <td>{{ \App\Models\Transfert::MODES[$reglement->mode_paiement] ?? '—' }}</td>
                        <td class="droite">{{ $ar($reglement->montant_envoye) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p class="gris" style="margin-top: 30px; text-align: center; font-size: 9px">
        Modes de paiement acceptés : espèces, MVola, Orange Money, Airtel Money, virement bancaire.
    </p>
</body>
</html>

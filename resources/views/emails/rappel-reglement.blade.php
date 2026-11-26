@php
    $commande = $facture->commande;
    $client = $commande->client;
    $ar = fn ($v) => number_format($v, 0, ',', ' ') . ' Ar';
@endphp
<!DOCTYPE html>
<html lang="fr">
<body style="margin:0;padding:24px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:10px;padding:28px">
        <p style="margin:0 0 20px;font-size:20px;font-weight:bold;color:#1e1b4b">D'ORINTS</p>

        <p>Bonjour {{ $client->prenom }} {{ $client->nom }},</p>

        <p>Sauf erreur de notre part, la facture <strong>{{ $facture->numero_facture }}</strong> du {{ $facture->created_at->format('d/m/Y') }}
           (commande {{ $commande->numero_commande }}) n'est pas encore entièrement réglée.</p>

        <table style="width:100%;border-collapse:collapse;margin:18px 0;font-size:14px">
            <tr><td style="padding:6px 0;color:#475569">Montant de la facture</td><td style="padding:6px 0;text-align:right">{{ $ar($facture->montant) }}</td></tr>
            <tr><td style="padding:6px 0;color:#475569">Déjà réglé</td><td style="padding:6px 0;text-align:right">{{ $ar($commande->montantPaye()) }}</td></tr>
            <tr><td style="padding:8px 0;border-top:1px solid #e2e8f0;font-weight:bold">Reste à payer</td><td style="padding:8px 0;border-top:1px solid #e2e8f0;text-align:right;font-weight:bold;color:#b91c1c">{{ $ar($commande->resteAPayer()) }}</td></tr>
        </table>

        <p>Vous trouverez votre facture en pièce jointe (PDF).</p>

        <p>Vous pouvez régler en espèces à notre agence, ou par MVola, Orange Money, Airtel Money ou virement bancaire.</p>

        <p style="color:#475569;font-size:13px">Si vous avez déjà effectué ce paiement, merci de ne pas tenir compte de ce message.</p>

        <p>Cordialement,<br>L'équipe D'ORINTS</p>
    </div>
</body>
</html>

{{-- Mise en page commune des e-mails envoyés aux clients --}}
<!DOCTYPE html>
<html lang="fr">
<body style="margin:0;padding:24px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;font-size:15px;line-height:1.5">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:10px;padding:28px">
        <p style="margin:0 0 20px;font-size:20px;font-weight:bold;color:#1e40af">D'ORINTS</p>
        @yield('contenu')
        <p style="margin-top:24px">Cordialement,<br>L'équipe D'ORINTS</p>
    </div>
    <p style="max-width:560px;margin:12px auto 0;text-align:center;font-size:12px;color:#64748b">D'ORINTS · Antananarivo, Madagascar</p>
</body>
</html>

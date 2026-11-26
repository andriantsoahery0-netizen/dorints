<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    protected $fillable = [
        'numero_facture',
        'commande_id',
        'montant',
        'user_id',
        'derniere_relance_at',
    ];

    protected $casts = [
        'derniere_relance_at' => 'datetime',
    ];

    // Montant déjà réglé sur la facture (règlements en ariary de sa commande)
    private const SQL_REGLE = "(select coalesce(sum(t.montant_envoye), 0) from transferts t
        where t.commande_id = factures.commande_id and t.devise_source = 'MGA')";

    // Factures dont il reste quelque chose à payer
    public function scopeNonReglees(Builder $query): Builder
    {
        return $query->whereRaw('factures.montant > ' . self::SQL_REGLE);
    }

    // Factures entièrement réglées
    public function scopeReglees(Builder $query): Builder
    {
        return $query->whereRaw('factures.montant <= ' . self::SQL_REGLE);
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    // L'utilisateur qui a validé la commande et émis la facture
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Message court de rappel, pour WhatsApp
    public function messageRappel(): string
    {
        $commande = $this->commande;
        $reste = number_format($commande->resteAPayer(), 0, ',', ' ');

        return "Bonjour {$commande->client->prenom}, c'est D'ORINTS. "
            . "Il reste {$reste} Ar à régler sur votre facture {$this->numero_facture} "
            . "(commande {$commande->numero_commande}). "
            . "Vous pouvez payer à l'agence, par MVola, Orange Money, Airtel Money ou virement. Merci !";
    }

    // Numéro au format international pour WhatsApp : 034 12 345 67 -> 261341234567
    public function numeroWhatsapp(): ?string
    {
        $chiffres = preg_replace('/\D/', '', (string) $this->commande->client->telephone);
        if ($chiffres === '') {
            return null;
        }
        // Accepte 034 12 345 67, +261 34 12 345 67, 00261 34… ou 34 12 345 67
        if (str_starts_with($chiffres, '00')) {
            $chiffres = substr($chiffres, 2);
        } elseif (str_starts_with($chiffres, '0')) {
            $chiffres = '261' . substr($chiffres, 1);
        } elseif (strlen($chiffres) === 9) {
            $chiffres = '261' . $chiffres;
        }

        return $chiffres;
    }

    // Génère un numéro unique du type FAC-2026-0001
    public static function genererNumero(): string
    {
        $annee = now()->year;

        $dernierNumero = self::where('numero_facture', 'like', "FAC-{$annee}-%")
            ->orderByDesc('numero_facture')
            ->value('numero_facture');

        $sequence = $dernierNumero ? ((int) substr($dernierNumero, -4)) + 1 : 1;

        return sprintf('FAC-%d-%04d', $annee, $sequence);
    }
}

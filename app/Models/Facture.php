<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    protected $fillable = [
        'numero_facture',
        'commande_id',
        'montant',
        'user_id',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    // L'utilisateur qui a validé la commande et émis la facture
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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

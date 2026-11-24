<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfert extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'client_id',
        'montant_envoye',
        'devise_source',
        'devise_cible',
        'taux_applique',
        'montant_converti',
        'mode_paiement',
        'user_id',
    ];

    // Libellés des modes de paiement
    public const MODES = [
        'especes' => 'Espèces',
        'mvola' => 'MVola',
        'orange_money' => 'Orange Money',
        'airtel_money' => 'Airtel Money',
        'virement' => 'Virement bancaire',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // L'agent qui a enregistré le transfert
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

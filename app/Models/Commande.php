<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_commande',
        'client_id',
        'description',
        'montant_total',
        'statut',
        'user_id',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // L'agent qui a créé la commande
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transferts(): HasMany
    {
        return $this->hasMany(Transfert::class);
    }

    // Total payé par le client (transferts en ariary)
    public function montantPaye(): float
    {
        return (float) $this->transferts()->where('devise_source', 'MGA')->sum('montant_envoye');
    }

    // Ce qu'il reste à payer
    public function resteAPayer(): float
    {
        return max(0, $this->montant_total - $this->montantPaye());
    }

    // non_paye, partiel ou paye
    public function statutPaiement(): string
    {
        if ($this->montantPaye() <= 0) {
            return 'non_paye';
        }

        return $this->resteAPayer() > 0 ? 'partiel' : 'paye';
    }
}
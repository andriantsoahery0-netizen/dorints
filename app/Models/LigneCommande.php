<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneCommande extends Model
{
    protected $table = 'lignes_commande';

    protected $fillable = [
        'commande_id',
        'designation',
        'quantite',
        'prix_unitaire',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    // Quantité × prix unitaire
    public function sousTotal(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }
}

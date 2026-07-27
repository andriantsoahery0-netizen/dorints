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
}

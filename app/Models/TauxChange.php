<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TauxChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'taux_mga_vers_eur',
        'taux_eur_vers_mga',
        'actif',
        'user_id',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    //
     public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Raccourci pour récupérer le taux actuellement actif
    public static function actuel(): ?self
    {
        return self::where('actif', true)->latest()->first();
    }
}

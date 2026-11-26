<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'actif' => 'boolean',
    ];
}

    // Le nom complet (colonne name) est toujours « NOM Prénom »
    protected static function booted(): void
    {
        static::saving(function (User $user) {
            // Compte créé avec un seul champ « name » (ex. « Jean Rakoto ») : on le découpe
            if (blank($user->nom) && filled($user->name)) {
                $mots = preg_split('/\s+/', trim($user->name), -1, PREG_SPLIT_NO_EMPTY);
                $user->nom = array_pop($mots);
                $user->prenom = implode(' ', $mots);
            }
            if (filled($user->nom)) {
                $user->name = trim($user->nom . ' ' . $user->prenom);
            }
        });
    }

    // Le nom est toujours enregistré (et donc affiché) en majuscules
    protected function nom(): Attribute
    {
        return Attribute::make(
            set: fn ($valeur) => $valeur === null ? null : mb_strtoupper(trim($valeur), 'UTF-8')
        );
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function transferts(): HasMany
    {
        return $this->hasMany(Transfert::class);
    }

    // Le prénom commence toujours par une majuscule (« tiana » -> « Tiana »)
    protected function prenom(): Attribute
    {
        return Attribute::make(set: fn ($valeur) => \App\Support\Prenom::formater($valeur));
    }
}

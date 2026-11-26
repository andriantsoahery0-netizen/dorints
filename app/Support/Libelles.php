<?php

namespace App\Support;

use Illuminate\Notifications\DatabaseNotification;

class Libelles
{
    private const STATUTS = [
        'en_attente' => 'En attente',
        'validee' => 'Validée',
        'en_cours' => 'En cours',
        'livree' => 'Livrée',
    ];

    // Message d'une notification, avec des statuts lisibles (aussi pour les anciennes)
    public static function notification(DatabaseNotification $notification): string
    {
        return strtr($notification->data['message'] ?? '', self::STATUTS);
    }
}

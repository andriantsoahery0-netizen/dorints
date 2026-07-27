<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TauxChangeModifie extends Notification
{
    use Queueable;

    public function __construct(
        public float $tauxEurVersMga,
        public string $nomAdmin
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->nomAdmin} a mis à jour le taux : 1 EUR = {$this->tauxEurVersMga} MGA",
        ];
    }
}
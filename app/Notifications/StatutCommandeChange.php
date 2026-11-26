<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatutCommandeChange extends Notification
{
    use Queueable;

    public function __construct(
        public Commande $commande,
        public string $ancienStatut,
        public string $nouveauStatut
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'commande_id' => $this->commande->id,
            'numero_commande' => $this->commande->numero_commande,
            'ancien_statut' => $this->ancienStatut,
            'nouveau_statut' => $this->nouveauStatut,
            'message' => "Commande {$this->commande->numero_commande} : " . self::libelle($this->ancienStatut) . ' → ' . self::libelle($this->nouveauStatut),
        ];
    }

    // en_attente -> « En attente », pour un message lisible
    public static function libelle(string $statut): string
    {
        return ['en_attente' => 'En attente', 'validee' => 'Validée', 'en_cours' => 'En cours', 'livree' => 'Livrée'][$statut] ?? $statut;
    }
}

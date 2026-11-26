<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\Client;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// Envoi d'un e-mail automatique au client. Ne bloque jamais l'action en cours :
// si le client n'a pas d'adresse ou si l'envoi échoue, on continue sans e-mail.
class EmailClient
{
    public static function envoyer(Client $client, Mailable $email, string $objet): bool
    {
        if (! $client->email) {
            return false;
        }

        try {
            Mail::to($client->email)->send($email);
        } catch (\Throwable $e) {
            Log::warning("E-mail au client non envoyé ({$objet}) : " . $e->getMessage());

            return false;
        }

        ActivityLog::enregistrer('email_client', "E-mail « {$objet} » envoyé à {$client->email}");

        return true;
    }

    // Phrase ajoutée au message de confirmation
    public static function mention(bool $envoye): string
    {
        return $envoye ? ' Un e-mail a été envoyé au client.' : '';
    }
}

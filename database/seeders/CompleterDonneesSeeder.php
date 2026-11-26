<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Complète la base avec l'administrateur, les agents et les clients
 * présentés dans le mémoire. Ne supprime rien : un compte ou un client
 * déjà présent est mis à jour, les autres sont ajoutés.
 * Lancer : php artisan db:seed --class=CompleterDonneesSeeder
 */
class CompleterDonneesSeeder extends Seeder
{
    public function run(): void
    {
        // Administrateur et agents (mot de passe : password)
        $utilisateurs = [
            ['Jean Rakoto', 'admin@dorints.mg', 'admin', true],
            ['Tiana Rabe', 'tiana@dorints.mg', 'agent', true],
            ['Miora Soa', 'miora@dorints.mg', 'agent', true],
            ['Paul Randria', 'paul@dorints.mg', 'agent', false],
        ];

        foreach ($utilisateurs as [$nom, $email, $role, $actif]) {
            $user = User::firstOrNew(['email' => $email]);
            $user->name = $nom;
            $user->role = $role;
            $user->actif = $actif;
            if (! $user->exists) {
                $user->password = bcrypt('password');
            }
            $user->save();
        }

        // Clients : nom, prénom, téléphone, adresse, e-mail
        $clients = [
            ['Rasoa', 'Marie', '034 12 345 67', 'Lot II M 45 Ambanidia, Antananarivo', 'marie.rasoa@gmail.com'],
            ['Rabe', 'Andry', '033 45 678 90', 'Lot IVG 12 Analakely, Antananarivo', null],
            ['Ravao', 'Fanja', '032 98 765 43', 'Tanambao, Fianarantsoa', 'fanja.ravao@yahoo.fr'],
            ['Randria', 'Hery', '034 55 111 22', 'Ankorondrano, Antananarivo', null],
            ['Rakotobe', 'Lalao', '038 22 333 44', 'Isotry, Antananarivo', 'lalao.rkb@gmail.com'],
            ['Razafy', 'Tojo', '034 77 888 99', 'Andravoahangy, Antananarivo', null],
        ];

        foreach ($clients as [$nom, $prenom, $telephone, $adresse, $email]) {
            $client = Client::firstOrNew(['nom' => mb_strtoupper($nom, 'UTF-8'), 'prenom' => $prenom]);
            $client->telephone = $telephone;
            $client->adresse = $adresse;
            // On garde l'e-mail déjà saisi s'il y en a un
            $client->email = $client->email ?: $email;
            $client->save();
        }

        $this->command->info('Administrateur, agents et clients du mémoire ajoutés ou mis à jour.');
        $this->command->info('Connexion : admin@dorints.mg / password');
    }
}

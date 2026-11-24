<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Remet la démonstration à zéro : supprime les commandes, leurs produits,
 * les factures, les règlements et transferts, les notifications et le journal.
 * Garde les clients, les utilisateurs et les taux de change.
 * Lancer : php artisan db:seed --class=ViderCommandesSeeder
 */
class ViderCommandesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach (['transferts', 'factures', 'lignes_commande', 'commandes', 'notifications', 'activity_logs'] as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info('Commandes, factures et transferts supprimés. Clients, utilisateurs et taux conservés.');
    }
}

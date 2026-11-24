<?php

namespace Database\Seeders;

use App\Models\{User, Client, Commande, LigneCommande, Transfert, TauxChange, ActivityLog, Facture};
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Données fictives pour la démonstration.
 * Lancer : php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {

        $admin = User::create(['name' => 'Jean Rakoto', 'email' => 'admin@dorints.mg', 'password' => bcrypt('password'), 'role' => 'admin', 'actif' => true]);
        $a1 = User::create(['name' => 'Tiana Rabe', 'email' => 'tiana@dorints.mg', 'password' => bcrypt('password'), 'role' => 'agent', 'actif' => true]);
        $a2 = User::create(['name' => 'Miora Soa', 'email' => 'miora@dorints.mg', 'password' => bcrypt('password'), 'role' => 'agent', 'actif' => true]);
        $a3 = User::create(['name' => 'Paul Randria', 'email' => 'paul@dorints.mg', 'password' => bcrypt('password'), 'role' => 'agent', 'actif' => false]);

        $t = function ($m, $date) { $m->created_at = $date; $m->updated_at = $date; $m->save(); return $m; };

        foreach ([[4870, '2026-09-15'], [4900, '2026-10-02'], [4950, '2026-10-20']] as $i => [$v, $d]) {
            $t(new TauxChange(['taux_eur_vers_mga' => $v, 'taux_mga_vers_eur' => round(1 / $v, 8), 'actif' => $i === 2, 'user_id' => $admin->id]), Carbon::parse($d . ' 08:30'));
        }

        $clients = [];
        foreach ([
            ['Rasoa', 'Marie', '034 12 345 67', 'Lot II M 45 Ambanidia, Antananarivo', 'marie.rasoa@gmail.com'],
            ['Rabe', 'Andry', '033 45 678 90', 'Lot IVG 12 Analakely, Antananarivo', null],
            ['Ravao', 'Fanja', '032 98 765 43', 'Tanambao, Fianarantsoa', 'fanja.ravao@yahoo.fr'],
            ['Randria', 'Hery', '034 55 111 22', 'Ankorondrano, Antananarivo', null],
            ['Rakotobe', 'Lalao', '038 22 333 44', 'Isotry, Antananarivo', 'lalao.rkb@gmail.com'],
            ['Razafy', 'Tojo', '034 77 888 99', 'Andravoahangy, Antananarivo', null],
        ] as $i => [$n, $p, $tel, $adr, $mail]) {
            $clients[] = $t(new Client(['nom' => $n, 'prenom' => $p, 'telephone' => $tel, 'adresse' => $adr, 'email' => $mail]), Carbon::parse('2026-09-' . (10 + $i) . ' 10:00'));
        }

        $cmds_ = [];
        $cmds = [
            [0, 'Smartphone Samsung Galaxy A15, 128 Go, noir', 'livree', '2026-10-03', $a1],
            [1, 'Ordinateur portable HP 15 pouces, Core i5, 16 Go', 'livree', '2026-10-06', $a2],
            [2, 'Imprimante HP LaserJet et deux cartouches', 'en_cours', '2026-10-12', $a1],
            [3, 'Smartphone Tecno Spark 20, 256 Go', 'en_cours', '2026-10-18', $a2],
            [4, 'Coffret de cosmétiques et parfum', 'validee', '2026-10-22', $a1],
            [5, 'Tablette Lenovo Tab M10 avec étui', 'en_attente', '2026-10-25', $a1],
            [0, 'Écouteurs sans fil et chargeur rapide', 'en_attente', '2026-10-27', $a2],
        ];
        // Produits commandés : désignation, quantité, prix unitaire (le total donne le montant de la commande)
        $produits = [
            [['Smartphone Samsung Galaxy A15, 128 Go, noir', 1, 850000]],
            [['Ordinateur portable HP 15 pouces, Core i5, 16 Go', 1, 2750000], ['Souris sans fil Logitech M185', 2, 110000]],
            [['Imprimante HP LaserJet M110w', 1, 1100000], ['Cartouche de toner HP 142A', 2, 200000]],
            [['Smartphone Tecno Spark 20, 256 Go', 1, 742500]],
            [['Parfum Dior Sauvage 100 ml', 1, 270000], ['Coffret de soins Nivea', 1, 150000]],
            [['Tablette Lenovo Tab M10, 64 Go', 1, 900000], ['Étui de protection pour tablette', 1, 80000]],
            [['Écouteurs sans fil JBL Tune', 2, 55000], ['Chargeur rapide USB-C 25 W', 1, 50000]],
        ];
        foreach ($cmds as $i => [$c, $desc, $st, $d, $u]) {
            $desc = [null, 'Livraison à domicile demandée', null, null, 'Emballage cadeau', null, null][$i];
            $cmds_[] = $cmd = $t(new Commande(['numero_commande' => sprintf('CMD-2026-%04d', $i + 1), 'client_id' => $clients[$c]->id, 'description' => $desc, 'montant_total' => [850000, 2970000, 1500000, 742500, 420000, 980000, 160000][$i], 'statut' => $st, 'user_id' => $u->id]), Carbon::parse($d . ' 09:15'));
            foreach ($produits[$i] as [$designation, $qte, $pu]) {
                $t(new LigneCommande(['commande_id' => $cmd->id, 'designation' => $designation, 'quantite' => $qte, 'prix_unitaire' => $pu]), Carbon::parse($d . ' 09:15'));
            }
            if ($st !== 'en_attente') {
                $t(new Facture(['numero_facture' => sprintf('FAC-2026-%04d', $i + 1), 'commande_id' => $cmd->id, 'montant' => $cmd->montant_total, 'user_id' => $u->id]), Carbon::parse($d . ' 10:00'));
            }
            if ($i < 4) {
                $taux = $i < 2 ? 4900 : 4950;
                $mga = [850000, 2970000, 1188000, 742500][$i];
                $t(new Transfert(['commande_id' => $cmd->id, 'client_id' => $clients[$c]->id, 'montant_envoye' => $mga, 'devise_source' => 'MGA', 'devise_cible' => 'EUR', 'taux_applique' => round(1 / $taux, 8), 'montant_converti' => round($mga / $taux, 2), 'mode_paiement' => ['mvola', 'virement', 'especes', 'orange_money'][$i], 'user_id' => $u->id]), Carbon::parse($d . ' 09:40'));
            }
        }
        $t(new Transfert(['commande_id' => $cmds_[1]->id, 'client_id' => $clients[1]->id, 'montant_envoye' => 50, 'devise_source' => 'EUR', 'devise_cible' => 'MGA', 'taux_applique' => 4900, 'montant_converti' => 245000, 'mode_paiement' => 'virement', 'user_id' => $a2->id]), Carbon::parse('2026-10-09 14:10'));

        foreach ([
            [$admin, 'taux', 'Nouveau taux défini : 1 EUR = 4 950 MGA', '2026-10-20 08:30'],
            [$a1, 'commande', 'Création de la commande CMD-2026-0005', '2026-10-22 09:15'],
            [$a1, 'commande', 'Création de la commande CMD-2026-0006', '2026-10-25 09:15'],
            [$a2, 'statut', 'CMD-2026-0004 : en attente → en cours', '2026-10-26 11:02'],
            [$a2, 'commande', 'Création de la commande CMD-2026-0007', '2026-10-27 09:15'],
            [$admin, 'utilisateur', 'Désactivation du compte de Paul Randria', '2026-10-27 16:40'],
            [$a1, 'statut', 'CMD-2026-0003 : en attente → en cours', '2026-10-28 10:20'],
        ] as [$u, $a, $desc, $d]) {
            $t(new ActivityLog(['user_id' => $u->id, 'action' => $a, 'description' => $desc]), Carbon::parse($d));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Support\Csv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

// Sauvegarde complète des données, réservée à l'administrateur
class SauvegardeController extends Controller
{
    // Ce que contient la sauvegarde : table => nom du fichier
    private const TABLES = [
        'clients' => 'clients',
        'commandes' => 'commandes',
        'lignes_commande' => 'produits_commandes',
        'factures' => 'factures',
        'transferts' => 'reglements_et_transferts',
        'taux_changes' => 'taux_de_change',
        'users' => 'utilisateurs',
        'activity_logs' => 'journal_activite',
    ];

    // Libellés affichés sur la page
    private const LIBELLES = [
        'clients' => 'Clients',
        'commandes' => 'Commandes',
        'lignes_commande' => 'Produits commandés',
        'factures' => 'Factures',
        'transferts' => 'Règlements et transferts',
        'taux_changes' => 'Taux de change',
        'users' => 'Utilisateurs (sans les mots de passe)',
        'activity_logs' => "Journal d'activité",
    ];

    // Colonnes jamais exportées
    private const MASQUEES = ['password', 'remember_token'];

    public function index()
    {
        $tables = [];
        foreach (self::TABLES as $table => $fichier) {
            $tables[] = ['nom' => self::LIBELLES[$table], 'fichier' => "{$fichier}.csv", 'lignes' => DB::table($table)->count()];
        }

        return view('sauvegarde', compact('tables'));
    }

    public function telecharger()
    {
        $date = now()->format('Y-m-d_H-i');

        // Un fichier CSV par table, réunis dans un ZIP
        if (class_exists(ZipArchive::class)) {
            $chemin = storage_path("app/sauvegarde-dorints-{$date}.zip");
            $zip = new ZipArchive();
            $zip->open($chemin, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach (self::TABLES as $table => $fichier) {
                [$entetes, $lignes] = $this->contenuTable($table);
                $zip->addFromString("{$fichier}.csv", Csv::contenu($entetes, $lignes));
            }
            $zip->close();

            ActivityLog::enregistrer('sauvegarde', 'Téléchargement de la sauvegarde complète des données');

            return response()->download($chemin)->deleteFileAfterSend();
        }

        // Sans l'extension zip de PHP : un seul fichier JSON
        $donnees = [];
        foreach (self::TABLES as $table => $fichier) {
            $donnees[$fichier] = DB::table($table)->get()
                ->map(fn ($ligne) => collect((array) $ligne)->except(self::MASQUEES))->all();
        }

        ActivityLog::enregistrer('sauvegarde', 'Téléchargement de la sauvegarde complète des données');

        return response()->streamDownload(
            fn () => print(json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)),
            "sauvegarde-dorints-{$date}.json",
            ['Content-Type' => 'application/json']
        );
    }

    // En-têtes et lignes d'une table, sans les colonnes masquées
    private function contenuTable(string $table): array
    {
        $lignes = DB::table($table)->get()->map(fn ($l) => collect((array) $l)->except(self::MASQUEES)->all());

        $entetes = $lignes->isNotEmpty()
            ? array_keys($lignes->first())
            : array_values(array_diff(Schema::getColumnListing($table), self::MASQUEES));

        return [$entetes, $lignes->map(fn ($l) => array_values($l))->all()];
    }
}

<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

// Fichiers CSV lisibles directement par Excel (UTF-8 avec BOM, séparateur « ; »)
class Csv
{
    public static function telecharger(string $nomFichier, array $entetes, iterable $lignes): StreamedResponse
    {
        return response()->streamDownload(function () use ($entetes, $lignes) {
            $sortie = fopen('php://output', 'w');
            fwrite($sortie, "\xEF\xBB\xBF");
            fputcsv($sortie, $entetes, ';');
            foreach ($lignes as $ligne) {
                fputcsv($sortie, $ligne, ';');
            }
            fclose($sortie);
        }, $nomFichier, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // Écrit un CSV dans une chaîne (pour la sauvegarde)
    public static function contenu(array $entetes, iterable $lignes): string
    {
        $flux = fopen('php://temp', 'r+');
        fwrite($flux, "\xEF\xBB\xBF");
        fputcsv($flux, $entetes, ';');
        foreach ($lignes as $ligne) {
            fputcsv($flux, $ligne, ';');
        }
        rewind($flux);
        $contenu = stream_get_contents($flux);
        fclose($flux);

        return $contenu;
    }

    // Montant au format d'Excel en français : 1500000 ou 240,5
    public static function nombre($valeur): string
    {
        return str_replace('.', ',', (string) round((float) $valeur, 2));
    }
}

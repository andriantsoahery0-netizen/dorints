<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Nom et prénom séparés pour les utilisateurs, nom en majuscules
// (la colonne name garde le nom complet « NOM Prénom » pour l'affichage)
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nom')->nullable()->after('name');
            $table->string('prenom')->nullable()->after('nom');
        });

        // « Jean Rakoto » : le dernier mot devient le nom, le reste le prénom
        foreach (DB::table('users')->select('id', 'name')->get() as $user) {
            $mots = preg_split('/\s+/', trim((string) $user->name), -1, PREG_SPLIT_NO_EMPTY);
            $nom = mb_strtoupper((string) array_pop($mots), 'UTF-8');
            $prenom = implode(' ', $mots);
            DB::table('users')->where('id', $user->id)->update([
                'nom' => $nom,
                'prenom' => $prenom,
                'name' => trim($nom . ' ' . $prenom),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom', 'prenom']);
        });
    }
};

<?php

use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Numéros au format 034 12 345 67 et noms des clients en majuscules
return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('clients')->select('id', 'nom', 'telephone')->get() as $client) {
            DB::table('clients')->where('id', $client->id)->update([
                'nom' => mb_strtoupper(trim($client->nom), 'UTF-8'),
                'telephone' => Client::formaterTelephone($client->telephone),
            ]);
        }
    }

    public function down(): void
    {
        // Les données restent au nouveau format
    }
};

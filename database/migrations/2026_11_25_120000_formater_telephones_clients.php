<?php

use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Met les numéros déjà enregistrés au format +261 34 12 345 67
return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('clients')->select('id', 'telephone')->get() as $client) {
            $formate = Client::formaterTelephone($client->telephone);
            if ($formate !== $client->telephone) {
                DB::table('clients')->where('id', $client->id)->update(['telephone' => $formate]);
            }
        }
    }

    public function down(): void
    {
        // Les numéros restent au nouveau format
    }
};

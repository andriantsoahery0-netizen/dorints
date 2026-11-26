<?php

use App\Support\Prenom;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Prénoms déjà enregistrés : première lettre de chaque prénom en majuscule
return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('clients')->select('id', 'prenom')->get() as $c) {
            DB::table('clients')->where('id', $c->id)->update(['prenom' => Prenom::formater($c->prenom)]);
        }
        foreach (DB::table('users')->select('id', 'nom', 'prenom')->get() as $u) {
            $prenom = Prenom::formater($u->prenom);
            DB::table('users')->where('id', $u->id)->update([
                'prenom' => $prenom,
                'name' => trim($u->nom . ' ' . $prenom),
            ]);
        }
    }

    public function down(): void
    {
        // Les prénoms restent mis en forme
    }
};

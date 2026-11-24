<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Les produits commandés : une ligne par produit
        Schema::create('lignes_commande', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
            $table->string('designation');
            $table->unsignedInteger('quantite');
            $table->decimal('prix_unitaire', 15, 2);
            $table->timestamps();
        });

        // La description devient une remarque facultative
        Schema::table('commandes', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });

        // Reprise des commandes existantes : leur description devient une ligne unique
        foreach (DB::table('commandes')->get() as $commande) {
            DB::table('lignes_commande')->insert([
                'commande_id' => $commande->id,
                'designation' => $commande->description ?: 'Commande ' . $commande->numero_commande,
                'quantite' => 1,
                'prix_unitaire' => $commande->montant_total,
                'created_at' => $commande->created_at,
                'updated_at' => $commande->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_commande');

        DB::table('commandes')->whereNull('description')->update(['description' => '']);

        Schema::table('commandes', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });
    }
};

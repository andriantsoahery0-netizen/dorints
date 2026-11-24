<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nouveau statut « validee » entre « en_attente » et « en_cours »
        Schema::table('commandes', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'validee', 'en_cours', 'livree'])
                ->default('en_attente')
                ->change();
        });

        // Une facture par commande validée
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('numero_facture')->unique();
            $table->foreignId('commande_id')->unique()->constrained()->restrictOnDelete();
            $table->decimal('montant', 15, 2);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');

        DB::table('commandes')->where('statut', 'validee')->update(['statut' => 'en_attente']);

        Schema::table('commandes', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'en_cours', 'livree'])
                ->default('en_attente')
                ->change();
        });
    }
};

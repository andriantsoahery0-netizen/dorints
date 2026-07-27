<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transferts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->restrictOnDelete();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->decimal('montant_envoye', 15, 2);
            $table->enum('devise_source', ['MGA', 'EUR']);
            $table->enum('devise_cible', ['MGA', 'EUR']);
            $table->decimal('taux_applique', 15, 8)->comment('Taux figé au moment de la transaction');
            $table->decimal('montant_converti', 15, 2);
            $table->foreignId('user_id')->constrained()->comment('Agent ayant enregistré le transfert');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transferts');
    }
};

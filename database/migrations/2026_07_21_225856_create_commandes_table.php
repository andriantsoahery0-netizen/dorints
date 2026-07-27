<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_commande')->unique();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->text('description');
            $table->enum('statut', ['en_attente', 'en_cours', 'livree'])->default('en_attente');
            $table->foreignId('user_id')->constrained()->comment('Agent créateur');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};

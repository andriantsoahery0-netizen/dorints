<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taux_changes', function (Blueprint $table) {
            $table->id();
            $table->decimal('taux_mga_vers_eur', 15, 8);
            $table->decimal('taux_eur_vers_mga', 15, 4);
            $table->boolean('actif')->default(true);
            $table->foreignId('user_id')->constrained()->comment('Admin ayant défini ce taux');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taux_changes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->decimal('montant_total', 15, 2)->default(0)->after('description');
        });

        Schema::table('transferts', function (Blueprint $table) {
            $table->string('mode_paiement')->nullable()->after('montant_converti');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('montant_total');
        });

        Schema::table('transferts', function (Blueprint $table) {
            $table->dropColumn('mode_paiement');
        });
    }
};

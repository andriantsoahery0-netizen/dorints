<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Date de la dernière relance envoyée au client pour cette facture
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->timestamp('derniere_relance_at')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn('derniere_relance_at');
        });
    }
};

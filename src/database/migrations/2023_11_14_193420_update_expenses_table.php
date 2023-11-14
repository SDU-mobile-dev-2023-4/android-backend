<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Move payee_id to payer_id
        Schema::table('expenses', function (Blueprint $table) {
            $table->renameColumn('payee_id', 'payer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move payer_id to payee_id
        Schema::table('expenses', function (Blueprint $table) {
            $table->renameColumn('payer_id', 'payee_id');
        });
    }
};

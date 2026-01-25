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
        Schema::table('traffic_stats', function (Blueprint $table) {
            $table->float('rx_rate')->default(0)->change();
            $table->float('tx_rate')->default(0)->change();
            $table->renameColumn('stat_date', 'stat_timestamp');
        });

        Schema::table('traffic_stats', function (Blueprint $table) {
            $table->timestamp('stat_timestamp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traffic_stats', function (Blueprint $table) {
            $table->bigInteger('rx_rate')->default(0)->change();
            $table->bigInteger('tx_rate')->default(0)->change();
            $table->renameColumn('stat_timestamp', 'stat_date');
        });

        Schema::table('traffic_stats', function (Blueprint $table) {
            $table->date('stat_date')->change();
        });
    }
};
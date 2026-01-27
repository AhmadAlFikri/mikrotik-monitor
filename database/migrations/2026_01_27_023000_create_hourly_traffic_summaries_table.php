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
        Schema::create('hourly_traffic_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('interface_name');
            $table->bigInteger('avg_rx_rate');
            $table->bigInteger('avg_tx_rate');
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['user_name', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hourly_traffic_summaries');
    }
};

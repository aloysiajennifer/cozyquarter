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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // closed = 0/available = 1/reserved =2
            $table->tinyInteger('status_schedule')->default(0);
            $table->foreignId('id_operational_day')->constrained('operational_days')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_time')->constrained('times')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_cwspace')->constrained('cwspaces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_reservation')->nullable()->constrained('reservations')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

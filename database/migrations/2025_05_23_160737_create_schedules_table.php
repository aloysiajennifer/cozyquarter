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
            // room available or not, 0 = available 1=reserved
            $table->boolean('status_schedule')->default(0);
            $table->date('date');
            $table->foreignId('id_time')->constrained('times')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_cwspace')->constrained('cwspaces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_reservation')->constrained('reservations')->onUpdate('cascade')->onDelete('cascade');
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

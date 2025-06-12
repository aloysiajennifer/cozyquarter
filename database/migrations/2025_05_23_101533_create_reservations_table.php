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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('reservation_code_cwspace'); // Kode CW Space yang direservasi
            $table->date('reservation_date'); // Tanggal jadwal yang direservasi
            $table->time('reservation_start_time'); // Waktu mulai jadwal yang direservasi
            $table->time('reservation_end_time'); // Waktu selesai jadwal yang direservasi
            $table->tinyInteger('status_reservation')->nullable(); //0=reserved, 1= attended, 2=not attended 3=cancelled 4=closed
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->timestamp('timestamp_reservation');
            $table->string('purpose');
            $table->string('name');
            $table->string('contact');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

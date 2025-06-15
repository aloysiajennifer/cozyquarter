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
            $table->string('name');
            $table->string('email');
            $table->string('purpose');
            $table->string('reservation_code_cwspace'); // Kode CW Space yang direservasi
            $table->date('reservation_date'); // Tanggal jadwal yang direservasi
            $table->time('reservation_start_time'); // Waktu mulai jadwal yang direservasi
            $table->time('reservation_end_time'); // Waktu selesai jadwal yang direservasi
            $table->tinyInteger('status_reservation'); //0=reserved, 1= not attended, 2=attended 3=cancelled 4=closed
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->integer('num_participants'); // Jumlah peserta, default 1
            $table->timestamp('timestamp_reservation');
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

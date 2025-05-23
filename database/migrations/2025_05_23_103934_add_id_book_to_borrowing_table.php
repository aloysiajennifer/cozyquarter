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
        Schema::table('borrowing', function (Blueprint $table) {
            $table->foreignId('id_book')->constrained('book')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowing', function (Blueprint $table) {
            $table->dropForeign(['id_book']);
            $table->dropColumn('id_book');
        });
    }
};

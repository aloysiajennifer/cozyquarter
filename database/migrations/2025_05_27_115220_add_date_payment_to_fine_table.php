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
        Schema::table('fine', function (Blueprint $table) {
            $table->timestamp('date_finepayment')->nullable()->after('payment_status');
            $table->renameColumn('payment_status', 'status_fine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fine', function (Blueprint $table) {
            $table->dropColumn('date_finepayment');
            $table->renameColumn('status_fine', 'payment_status');
        });
    }
};

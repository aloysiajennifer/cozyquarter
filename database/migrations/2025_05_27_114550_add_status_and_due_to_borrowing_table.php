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
            $table->timestamp('return_date')->nullable()->change();
            $table->timestamp('return_due')->nullable()->after('borrowing_date');
            $table->boolean('status_returned')->default(0)->after('return_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowing', function (Blueprint $table) {
            $table->timestamp('return_date')->nullable(false)->change();
            $table->dropColumn(['return_due', 'status_returned']);
        });
    }
};

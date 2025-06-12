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
        Schema::create('cwspaces', function (Blueprint $table) {
            $table->id();
            $table->string('code_cwspace')->unique();
            $table->integer('capacity_cwspace');
            $table->boolean('status_cwspace')->default(1); //1 = open, 0 = closed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cwspaces');
    }
};

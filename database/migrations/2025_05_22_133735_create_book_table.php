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
        Schema::create('book', function (Blueprint $table) {
            $table->id();
            $table->string('title_book');
            $table->string('author_book');
            $table->string('isbn_book')->unique();
            $table->string('synopsis_book');
            $table->string('cover_book');
            $table->boolean('status_book')->default(1);
            $table->foreignId('id_category')->constrained('category')->onDelete('cascade');
            $table->foreignId('id_shelf')->constrained('shelf')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book');
    }
};

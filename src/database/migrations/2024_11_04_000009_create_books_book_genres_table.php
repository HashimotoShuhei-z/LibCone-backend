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
        Schema::create('books_book_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_genre_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books_book_genres', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });
        
        Schema::table('books_book_genres', function (Blueprint $table) {
            $table->dropForeign(['book_genre_id']);
        });

        Schema::dropIfExists('books_book_genres');
    }
};

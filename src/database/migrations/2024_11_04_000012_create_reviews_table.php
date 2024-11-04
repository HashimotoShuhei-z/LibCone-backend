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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_book_id')->constrained('companies_books')->cascadeOnDelete();
            $table->string('review_title');
            $table->string('review_content');
            $table->integer('review_rate');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['company_book_id']);
        });


        Schema::dropIfExists('reviews');
    }
};

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
        Schema::create('borrowed_book_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_book_id')->constrained('companies_books')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowed_book_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('borrowed_book_logs', function (Blueprint $table) {
            $table->dropForeign(['company_book_id']);
        });

        Schema::dropIfExists('borrowed_book_logs');
    }
};

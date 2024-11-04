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
        Schema::create('review_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stamp_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_reactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('review_reactions', function (Blueprint $table) {
            $table->dropForeign(['review_id']);
        });
        
        Schema::table('review_reactions', function (Blueprint $table) {
            $table->dropForeign(['stamp_id']);
        });

        Schema::dropIfExists('review_reactions');
    }
};

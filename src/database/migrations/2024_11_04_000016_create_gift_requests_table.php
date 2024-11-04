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
        Schema::create('gift_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('point_send_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('point_received_user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('point');
            $table->boolean('is_gifted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_requests', function (Blueprint $table) {
            $table->dropForeign(['point_send_user_id']);
        });

        Schema::table('gift_requests', function (Blueprint $table) {
            $table->dropForeign(['point_received_user_id']);
        });

        Schema::dropIfExists('gift_requests');
    }
};

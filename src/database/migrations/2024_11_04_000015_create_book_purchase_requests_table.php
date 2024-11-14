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
        Schema::create('book_purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('isbn');
            $table->string('book_title');
            $table->string('book_url')->nullable();
            $table->integer('book_price')->nullable();
            $table->integer('purchase_type'); # 0:個人購入, 1:会社購入
            $table->date('hope_deliver_at')->nullable();
            $table->integer('purchase_status')->default(0); # 0:未購入, 1:購入申請中, 2:配達中, 3:配達済み, 4:拒否
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_purchase_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('book_purchase_requests');
    }
};

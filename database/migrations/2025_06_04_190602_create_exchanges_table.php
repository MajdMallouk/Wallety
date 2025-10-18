<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('from_wallet_id')->constrained('wallets');
            $table->foreignId('to_wallet_id')->constrained('wallets')->nullable();
            $table->foreignId('from_currency_id')->constrained('currencies');
            $table->foreignId('to_currency_id')->constrained('currencies');
            $table->decimal('from_amount', 16, 8);
            $table->decimal('to_amount', 16, 8);
             $table->decimal('rate', 16, 8)->comment('to_amount / from_amount');
            $table->timestamps();
        });
;
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};

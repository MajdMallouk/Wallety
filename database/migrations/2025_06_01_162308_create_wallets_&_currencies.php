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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code')->default('USD');
            $table->string('currency_symbol');
            $table->string('currency_fullname');
            $table->boolean('currency_type')->comment('0 = FIAT, 1 = CRYPTO');
            $table->float('exchange_rate')->default(0);
            $table->boolean('is_default')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('balance', 16, 8)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('wallets');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // sender
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 16, 8);
            $table->text('message')->nullable();
            $table->foreignId('currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->string('status')->default('waiting');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

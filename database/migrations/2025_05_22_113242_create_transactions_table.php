<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->constrained('users')->onDelete('cascade');   //sender
            $table->unsignedBigInteger('receiver_id')->constrained('users')->onDelete('cascade');
            $table->integer('amount');
            $table->text('message')->nullable();
            $table->unsignedInteger('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->unsignedInteger('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->string('status')->default('waiting');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

<?php

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Str;

function makeCompletedUser(array $attributes = []): User
{
    return User::factory()->create(array_merge([
        'username' => Str::lower(Str::random(10)),
        'phone' => (string) random_int(1000000000, 1999999999),
        'completed_profile' => true,
    ], $attributes));
}

function makeCurrency(array $attributes = []): Currency
{
    return Currency::query()->create(array_merge([
        'currency_code' => 'USD',
        'currency_symbol' => '$',
        'currency_fullname' => 'US Dollar',
        'currency_type' => 0,
        'exchange_rate' => 1,
        'is_default' => 1,
        'status' => 1,
    ], $attributes));
}

test('authenticated users can transfer funds by username', function () {
    $currency = makeCurrency();
    $sender = makeCompletedUser(['username' => 'sender001']);
    $recipient = makeCompletedUser(['username' => 'recipient01']);

    $senderWallet = Wallet::query()->create([
        'user_id' => $sender->id,
        'currency_id' => $currency->id,
        'balance' => 500,
    ]);

    $response = $this
        ->actingAs($sender)
        ->post(route('transactions.store'), [
            'wallet_id' => $senderWallet->id,
            'receiver_id' => $recipient->username,
            'amount' => 150,
            'message' => 'For lunch',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('transactions', [
        'user_id' => $sender->id,
        'receiver_id' => $recipient->id,
        'amount' => 150,
        'status' => 'paid',
        'wallet_id' => $senderWallet->id,
    ]);

    expect((float) $senderWallet->refresh()->balance)->toBe(350.0);

    $recipientWallet = Wallet::query()
        ->where('user_id', $recipient->id)
        ->where('currency_id', $currency->id)
        ->first();

    expect($recipientWallet)->not->toBeNull();
    expect((float) $recipientWallet->balance)->toBe(150.0);
});

test('users cannot transfer money to themselves', function () {
    $currency = makeCurrency();
    $sender = makeCompletedUser(['username' => 'selfsend01']);
    $wallet = Wallet::query()->create([
        'user_id' => $sender->id,
        'currency_id' => $currency->id,
        'balance' => 500,
    ]);

    $response = $this
        ->actingAs($sender)
        ->post(route('transactions.store'), [
            'wallet_id' => $wallet->id,
            'receiver_id' => $sender->username,
            'amount' => 50,
        ]);

    $response->assertSessionHasErrors('receiver_id');
    expect((float) $wallet->refresh()->balance)->toBe(500.0);
    $this->assertDatabaseCount('transactions', 0);
});

test('users cannot send from wallets they do not own', function () {
    $currency = makeCurrency();
    $sender = makeCompletedUser(['username' => 'senderown01']);
    $recipient = makeCompletedUser(['username' => 'ownercheck1']);
    $walletOwner = makeCompletedUser(['username' => 'walletown01']);

    $foreignWallet = Wallet::query()->create([
        'user_id' => $walletOwner->id,
        'currency_id' => $currency->id,
        'balance' => 500,
    ]);

    $response = $this
        ->actingAs($sender)
        ->post(route('transactions.store'), [
            'wallet_id' => $foreignWallet->id,
            'receiver_id' => $recipient->username,
            'amount' => 50,
        ]);

    $response->assertSessionHasErrors('wallet_id');
    $this->assertDatabaseCount('transactions', 0);
});

test('transfer amount cannot exceed sender wallet balance', function () {
    $currency = makeCurrency();
    $sender = makeCompletedUser(['username' => 'balance001']);
    $recipient = makeCompletedUser(['username' => 'balance002']);
    $wallet = Wallet::query()->create([
        'user_id' => $sender->id,
        'currency_id' => $currency->id,
        'balance' => 20,
    ]);

    $response = $this
        ->actingAs($sender)
        ->post(route('transactions.store'), [
            'wallet_id' => $wallet->id,
            'receiver_id' => $recipient->username,
            'amount' => 100,
        ]);

    $response->assertSessionHasErrors('amount');
    expect((float) $wallet->refresh()->balance)->toBe(20.0);
    $this->assertDatabaseCount('transactions', 0);
});

test('users cannot view transactions they do not own', function () {
    $currency = makeCurrency();
    $sender = makeCompletedUser(['username' => 'viewuser01']);
    $recipient = makeCompletedUser(['username' => 'viewuser02']);
    $outsider = makeCompletedUser(['username' => 'viewuser03']);
    $wallet = Wallet::query()->create([
        'user_id' => $sender->id,
        'currency_id' => $currency->id,
        'balance' => 300,
    ]);

    $transaction = Transaction::query()->create([
        'user_id' => $sender->id,
        'receiver_id' => $recipient->id,
        'amount' => 50,
        'currency_id' => $currency->id,
        'wallet_id' => $wallet->id,
        'status' => 'paid',
    ]);

    $this->actingAs($outsider)
        ->get(route('transactions.show', $transaction))
        ->assertForbidden();
});

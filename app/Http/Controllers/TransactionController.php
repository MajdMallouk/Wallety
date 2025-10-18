<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = Transaction::with(['user', 'receiver', 'currency'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest()
            ->cursorPaginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $user = auth()->user()->load('wallets.currency');
        return view('transactions.create', compact('user'));
    }

    public function store(TransactionRequest $request)
    {
        $data = $request->validated();
        $normalized = Str::lower($data['receiver_id']);
        $sender = $request->user();

        // Fetch recipient
        try {
            $recipient = User::where('email', $normalized)
                ->orWhere('username', $normalized)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Recipient not found.']);
        }

        // Prevent self-transfer
        if ($recipient->id === $sender->id) {
            return back()
                ->withInput()
                ->withErrors(['error' => "You can't send money to yourself"]);
        }

        // Rate limiting
        $key = 'transactions:' . $sender->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput()
                ->withErrors([
                    'rate_limit' => "Too many attempts. Please try again in {$seconds} seconds."
                ]);
        }
        RateLimiter::hit($key);

        try {
            DB::transaction(function() use ($data, $sender, $recipient) {
                $senderWallet = Wallet::where('id', $data['wallet_id'])
                    ->where('user_id', $sender->id)
                    ->firstOrFail();

                $recipientWallet = Wallet::firstOrCreate(
                    [
                        'user_id'     => $recipient->id,
                        'currency_id' => $senderWallet->currency_id,
                    ],
                    ['balance' => 0]
                );

                if ($senderWallet->balance < $data['amount']) {
                    throw new \Exception('Insufficient balance');
                }

                $senderWallet->decrement('balance', $data['amount']);
                $recipientWallet->increment('balance', $data['amount']);

                Transaction::create([
                    'user_id'     => $sender->id,
                    'receiver_id' => $recipient->id,
                    'amount'      => $data['amount'],
                    'message'     => $data['message'] ?? null,
                    'status'      => 'paid',
                    'currency_id' => $senderWallet->currency_id,
                    'wallet_id'   => $senderWallet->id,
                ]);
            });

            return redirect()
                ->route('dashboard')
                ->with('success', 'Money sent!');

        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }
    public function createForUser(User $recipient)
    {
        $sender = auth()->user()->load('wallets.currency');
        return view('transactions.create', [
            'recipient' => $recipient,
            'sender'    => $sender,
        ]);
    }

}

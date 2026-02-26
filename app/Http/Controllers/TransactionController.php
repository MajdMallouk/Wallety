<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $transactions = Transaction::query()
            ->with(['user', 'receiver', 'currency'])
            ->relatedToUser($user)
            ->latest()
            ->cursorPaginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create(): View
    {
        $user = auth()->user()->load('wallets.currency');

        return view('transactions.create', compact('user'));
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $normalizedReceiver = Str::lower(trim((string) $data['receiver_id']));
        $sender = $request->user();

        try {
            $recipient = User::query()
                ->where('email', $normalizedReceiver)
                ->orWhere('username', $normalizedReceiver)
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Recipient not found.']);
        }

        if ($recipient->id === $sender->id) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'You can\'t send money to yourself']);
        }

        $key = "transactions:{$sender->id}";
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->withInput()
                ->withErrors([
                    'rate_limit' => "Too many attempts. Please try again in {$seconds} seconds.",
                ]);
        }

        RateLimiter::hit($key);

        try {
            $amount = (float) $data['amount'];
            DB::transaction(function () use ($amount, $data, $sender, $recipient): void {
                $senderWallet = Wallet::query()
                    ->where('id', $data['wallet_id'])
                    ->where('user_id', $sender->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $recipientWallet = Wallet::query()->firstOrCreate(
                    ['user_id' => $recipient->id, 'currency_id' => $senderWallet->currency_id],
                    ['balance' => 0],
                );
                $recipientWallet = Wallet::query()
                    ->whereKey($recipientWallet->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((float) $senderWallet->balance < $amount) {
                    throw new Exception('Insufficient balance.');
                }

                $senderWallet->decrement('balance', $amount);
                $recipientWallet->increment('balance', $amount);

                Transaction::create([
                    'user_id' => $sender->id,
                    'receiver_id' => $recipient->id,
                    'amount' => $amount,
                    'message' => $data['message'] ?? null,
                    'status' => 'paid',
                    'currency_id' => $senderWallet->currency_id,
                    'wallet_id' => $senderWallet->id,
                ]);
            });

            return redirect()
                ->route('dashboard')
                ->with('success', 'Money sent!');
        } catch (Exception $e) {
            Log::error('Transaction failed', [
                'sender_id' => $sender->id,
                'receiver' => $normalizedReceiver,
                'wallet_id' => $data['wallet_id'],
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Transaction failed. Please try again.']);
        }
    }

    public function show(Transaction $transaction): View
    {
        abort_unless($transaction->isOwnedByUser(auth()->id()), 403);
        $transaction->loadMissing(['user', 'receiver', 'currency']);

        return view('transactions.show', compact('transaction'));
    }

    public function createForUser(User $recipient): View|RedirectResponse
    {
        if ($recipient->id === auth()->id()) {
            return redirect()
                ->route('transactions.create')
                ->withErrors(['error' => 'You can\'t send money to yourself']);
        }

        $sender = auth()->user()->load('wallets.currency');

        return view('transactions.create', [
            'recipient' => $recipient,
            'sender' => $sender,
        ]);
    }
}

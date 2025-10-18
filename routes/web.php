<?php

use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\QrController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::middleware(['auth'])->get('/profile-complete',
    [ProfileController::class, 'completeProfile'])->name('profile.complete');
Route::post('/profile/complete', [ProfileController::class, 'submitCompletion'])->name('profile.complete.submit');

Route::middleware(['auth', 'check.profile'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user()->load('wallets.currency');
        $transactions = Transaction::with(['user', 'receiver', 'currency'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest()
            ->get();

        return view('dashboard')->with(['transactions' => $transactions]);
    })->name('dashboard');

    Route::controller(TransactionController::class)->group(function () {
        Route::get('/transactions', 'index')->name('transactions.index');
        Route::get('/transactions/create', 'create')->name('transactions.create');
        Route::post('/transactions', 'store')->name('transactions.store');
        Route::get('/transactions/{transaction}', 'show')->name('transactions.show');

        // QR Code sending
        Route::get('/send/{recipient:username}', 'createForUser')
            ->name('transfers.send');

    });

    Route::get('/qr', [QrController::class, 'show'])->name('user.qr');

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoices', 'index')->name('invoices.index');
        Route::get('/invoices/create', 'create')->name('invoices.create');
        Route::post('/invoices', 'store')->name('invoices.store');
        Route::get('/invoices/{transaction}', 'show')->name('invoices.show');
    });

    Route::controller(ExchangeController::class)->group(function () {
        Route::get('/exchanges', 'index')->name('exchanges.index');
        Route::get('/exchanges/create', 'create')->name('exchanges.create');
        Route::post('/exchanges', 'store')->name('exchanges.store');
    });

    Route::controller(RechargeController::class)->group(function () {
        Route::get('/recharges', 'index')->name('recharges.index');
        Route::get('/recharges/create', 'create')->name('recharges.create');
        Route::post('/recharges', 'store')->name('recharges.store');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

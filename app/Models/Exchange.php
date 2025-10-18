<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exchange extends Model
{
    protected $fillable = [
        'user_id',
        'from_wallet_id',
        'to_wallet_id',
        'from_currency_id',
        'to_currency_id',
        'from_amount',
        'to_amount',
        'rate',
    ];

    public function fromWallet(): belongsTo
    {
        return $this->belongsTo(Wallet::class, 'from_wallet_id');
    }

    public function toWallet(): belongsTo
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }

    public function fromCurrency(): belongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency(): belongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}

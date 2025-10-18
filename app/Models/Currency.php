<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'currency_code','currency_symbol','currency_fullname',
        'currency_type','exchange_rate','is_default','status'
    ];
    public function wallets() {
        return $this->hasMany(Wallet::class);
    }
}

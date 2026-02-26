<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeRelatedToUser(Builder $query, User|int $user): Builder
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $query->where(function (Builder $query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('receiver_id', $userId);
        });
    }

    public function isReceived(): bool
    {
        return $this->receiver_id === auth()->id();
    }

    public function isOwnedByUser(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $this->user_id === $userId || $this->receiver_id === $userId;
    }

}

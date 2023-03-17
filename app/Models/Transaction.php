<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $wallet_id
 * @property $type
 * @property $amount
 * @property $meta
 * @property $uuid
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'meta',
        'uuid',
    ];

    public const TYPES = [
        'WITHDRAW' => 'withdraw',
        'DEPOSIT' => 'deposit',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}

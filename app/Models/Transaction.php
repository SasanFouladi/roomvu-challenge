<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $wallet_id
 * @property string $type
 * @property float $amount
 * @property array $meta
 * @property string $reference_id
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'meta',
        'reference_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Transaction $transaction) {
            $transaction->type = $transaction->amount < 0 ? Transaction::TYPES['WITHDRAW'] : Transaction::TYPES['DEPOSIT'];
            $transaction->reference_id = Str::uuid();
        });
    }

    public const TYPES = [
        'WITHDRAW' => 'withdraw',
        'DEPOSIT' => 'deposit',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmoneyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'emoney_card_id',
        'transaction_number',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function emoneyCard()
    {
        return $this->belongsTo(EmoneyCard::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->transaction_number = 'TRX-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        });
    }
}

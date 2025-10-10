<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmoneyCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'card_number',
        'balance',
        'status',
        'issued_date',
        'expired_date',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'issued_date' => 'date',
        'expired_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function transactions()
    {
        return $this->hasMany(EmoneyCard::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getMaskedCardNumberAttribute()
    {
        return '****' . substr($this->card_number, -4);
    }
}
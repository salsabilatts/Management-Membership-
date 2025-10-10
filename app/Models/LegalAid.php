<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalAid extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'case_number',
        'case_title',
        'case_description',
        'case_type',
        'submission_date',
        'status',
        'institution_id',
        'verified_by',
        'verification_date',
        'notes',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'verification_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($legalAid) {
            $legalAid->case_number = 'LBH-' . now()->format('Y') . '-' . str_pad(self::count() + 1, 5, '0', STR_PAD_LEFT);
        });
    }
}

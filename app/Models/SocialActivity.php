<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'activity_name',
        'activity_type',
        'description',
        'activity_date',
        'location',
        'budget',
        'beneficiary_count',
        'status',
        'created_by',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
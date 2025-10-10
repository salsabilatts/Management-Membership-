<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'event_time',
        'location',
        'quota',
        'registered_count',
        'status',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->belongsToMany(Member::class, 'health_event_participants')
            ->withPivot('status', 'notes')
            ->withTimestamps();
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    public function getAvailableSlotsAttribute()
    {
        return $this->quota - $this->registered_count;
    }

    public function isFullAttribute()
    {
        return $this->registered_count >= $this->quota;
    }
}
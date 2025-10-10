<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nik',
        'full_name',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'photo',
        'membership_type_id',
        'status',
        'join_date',
        'expired_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'expired_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function umkm()
    {
        return $this->hasMany(Umkm::class);
    }

    public function educationAids()
    {
        return $this->hasMany(EducationAid::class);
    }

    public function legalAids()
    {
        return $this->hasMany(LegalAid::class);
    }

    public function emoneyCards()
    {
        return $this->hasMany(EmoneyCard::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_date', '<', now());
    }
}
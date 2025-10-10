<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationAid extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'program_type',
        'student_name',
        'school_name',
        'grade_level',
        'aid_amount',
        'academic_year',
        'status',
        'submission_date',
        'approval_date',
        'disbursement_date',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'aid_amount' => 'decimal:2',
        'submission_date' => 'date',
        'approval_date' => 'date',
        'disbursement_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }
}
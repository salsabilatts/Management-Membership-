<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'type',
        'address',
        'phone',
        'email',
        'contact_person',
        'status',
        'programs',
    ];

    protected $casts = [
        'programs' => 'array',
    ];
}

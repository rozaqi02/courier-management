<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'level',
        'vehicle_type',
        'vehicle_plate',
        'address',
        'status',
    ];

    protected $casts = [
        'level' => 'integer',
    ];
}

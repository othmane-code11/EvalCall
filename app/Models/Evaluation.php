<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'conseiller_id',
        'type',
        'date',
        'reference',
        'audio',
        'score',
        'has_ko',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'has_ko' => 'boolean',
    ];
}

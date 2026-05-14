<?php

namespace App\Models;

use App\Models\User;
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
        'signature',
        'score',
        'has_ko',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'has_ko' => 'boolean',
    ];

    public function conseiller()
    {
        return $this->belongsTo(User::class, 'conseiller_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}

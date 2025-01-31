<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';  // Spécifier explicitement le nom de la table
    
    protected $fillable = [
        'message',
        'level',
        'context',
        'created_at'
    ];

    protected $casts = [
        'context' => 'array',
        'created_at' => 'datetime',
    ];
} 
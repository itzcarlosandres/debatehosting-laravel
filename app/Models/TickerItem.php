<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TickerItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'hot',
        'order',
    ];

    protected $casts = [
        'hot' => 'boolean',
        'order' => 'integer',
    ];
}

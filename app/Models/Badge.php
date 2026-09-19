<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'label',
        'color',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];
}

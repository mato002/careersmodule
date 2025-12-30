<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'is_protected',
    ];

    protected $casts = [
        'is_protected' => 'boolean',
    ];

    /**
     * Get roles formatted for dropdowns: [key => name].
     */
    public static function forSelect(): array
    {
        return static::orderBy('name')
            ->pluck('name', 'key')
            ->toArray();
    }
}




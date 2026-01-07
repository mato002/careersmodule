<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIPrompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompt_type',
        'role',
        'content',
        'description',
        'is_active',
        'version',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    /**
     * Get the user who created this prompt
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this prompt
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get prompt by type and role
     */
    public static function getPrompt(string $promptType, ?string $role = null): ?self
    {
        return self::where('prompt_type', $promptType)
            ->where('is_active', true)
            ->where(function($query) use ($role) {
                $query->where('role', $role)
                      ->orWhereNull('role'); // Default prompts (no role)
            })
            ->orderByRaw('CASE WHEN role IS NULL THEN 1 ELSE 0 END') // Prefer role-specific, fallback to default
            ->orderBy('version', 'desc')
            ->first();
    }

    /**
     * Get all prompts for a role
     */
    public static function getPromptsForRole(?string $role = null): array
    {
        $prompts = self::where('is_active', true)
            ->where(function($query) use ($role) {
                $query->where('role', $role)
                      ->orWhereNull('role');
            })
            ->orderByRaw('CASE WHEN role IS NULL THEN 1 ELSE 0 END')
            ->orderBy('version', 'desc')
            ->get()
            ->groupBy('prompt_type')
            ->map(function($group) {
                return $group->first(); // Get the most recent version
            });

        return $prompts->toArray();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'handled_at',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->whereNull('handled_at');
    }

    public function markHandled(?string $notes = null): void
    {
        $this->update([
            'handled_at' => now(),
            'status' => 'handled',
            'admin_notes' => $notes ?? $this->admin_notes,
        ]);
    }

    public function replies()
    {
        return $this->hasMany(ContactMessageReply::class);
    }
}

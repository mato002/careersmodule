<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'unsubscribed_at',
    ];

    protected $casts = [
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Check if the subscriber is active (not unsubscribed).
     */
    public function isActive(): bool
    {
        return is_null($this->unsubscribed_at);
    }

    /**
     * Scope to get only active subscribers.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('unsubscribed_at');
    }

    /**
     * Scope to get unsubscribed subscribers.
     */
    public function scopeUnsubscribed($query)
    {
        return $query->whereNotNull('unsubscribed_at');
    }

    /**
     * Unsubscribe the subscriber.
     */
    public function unsubscribe(): void
    {
        $this->update(['unsubscribed_at' => now()]);
    }

    /**
     * Resubscribe the subscriber.
     */
    public function resubscribe(): void
    {
        $this->update(['unsubscribed_at' => null]);
    }
}

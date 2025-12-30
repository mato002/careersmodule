<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    /**
     * Subscribe to the newsletter.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = $validated['email'];

        // Check if already subscribed
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->isActive()) {
                return back()->with('newsletter_error', 'This email is already subscribed to our newsletter.');
            } else {
                // Resubscribe
                $subscriber->resubscribe();
                return back()->with('newsletter_status', 'You have been resubscribed to our newsletter. Thank you!');
            }
        }

        // Create new subscription
        NewsletterSubscriber::create([
            'email' => $email,
        ]);

        return back()->with('newsletter_status', 'Thank you for subscribing to our newsletter!');
    }

    /**
     * Unsubscribe from the newsletter.
     */
    public function unsubscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if (!$subscriber) {
            return redirect()->route('careers.index')
                ->with('newsletter_error', 'Email address not found in our newsletter list.');
        }

        if (!$subscriber->isActive()) {
            return redirect()->route('careers.index')
                ->with('newsletter_error', 'You are already unsubscribed from our newsletter.');
        }

        $subscriber->unsubscribe();

        return redirect()->route('home')
            ->with('newsletter_status', 'You have been successfully unsubscribed from our newsletter.');
    }

    /**
     * Send newsletter to all active subscribers.
     * This method fetches recent published posts and sends them to subscribers.
     * 
     * @param int $limit Number of recent posts to include (default: 5)
     * @param string|null $subject Custom subject line (optional)
     * @return array Result with count of emails sent
     */
    public static function sendNewsletter(int $limit = 5, ?string $subject = null): array
    {
        // Get recent published posts
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        // Get all active subscribers
        $subscribers = NewsletterSubscriber::active()->get();

        if ($subscribers->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No active subscribers found.',
                'sent' => 0,
            ];
        }

        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterMail($posts, $subject));
                $sent++;
            } catch (\Exception $e) {
                \Log::error('Failed to send newsletter to ' . $subscriber->email . ': ' . $e->getMessage());
                $failed++;
            }
        }

        return [
            'success' => true,
            'message' => "Newsletter sent to {$sent} subscribers." . ($failed > 0 ? " {$failed} failed." : ''),
            'sent' => $sent,
            'failed' => $failed,
        ];
    }
}

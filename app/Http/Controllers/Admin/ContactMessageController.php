<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use App\Services\MessagingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->date('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->date('end_date'));
        }

        $totalMessagesCount = ContactMessage::count();
        $filteredMessagesCount = $query->count();

        $messages = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $statusCounts = [
            'new' => ContactMessage::where('status', 'new')->count(),
            'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
            'handled' => ContactMessage::where('status', 'handled')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'statusCounts', 'totalMessagesCount', 'filteredMessagesCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->load('replies.sender');
        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function sendReply(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'channel' => 'required|in:email,sms,whatsapp',
            'message' => 'required|string|max:5000',
            'recipient' => 'required|string',
        ]);

        // Validate recipient based on channel
        if ($validated['channel'] === 'email') {
            $request->validate(['recipient' => 'email']);
        } else {
            $request->validate(['recipient' => 'regex:/^[0-9+\-\s()]+$/']);
        }

        // Create reply record
        $reply = ContactMessageReply::create([
            'contact_message_id' => $contactMessage->id,
            'sent_by' => auth()->id(),
            'channel' => $validated['channel'],
            'message' => $validated['message'],
            'recipient' => $validated['recipient'],
            'status' => 'pending',
        ]);

        // Send the message
        $messagingService = new MessagingService();
        $sent = $messagingService->send($reply);

        if ($sent) {
            // Update contact message status if needed
            if ($contactMessage->status === 'new') {
                $contactMessage->update(['status' => 'in_progress']);
            }

            return back()->with('status', 'Reply sent successfully via ' . strtoupper($validated['channel']) . '!');
        } else {
            return back()->withErrors(['message' => 'Failed to send reply. Please check the error and try again.']);
        }
    }

    public function update(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,in_progress,handled'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $contactMessage->fill($data);

        if ($data['status'] === 'handled' && ! $contactMessage->handled_at) {
            $contactMessage->handled_at = now();
        } elseif ($data['status'] !== 'handled') {
            $contactMessage->handled_at = null;
        }

        $contactMessage->save();

        return back()->with('status', 'Message updated successfully.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('status', 'Message deleted.');
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:contact_messages,id',
            'status' => 'required|in:new,in_progress,handled',
        ]);

        $count = ContactMessage::whereIn('id', $validated['message_ids'])
            ->update([
                'status' => $validated['status'],
                'handled_at' => $validated['status'] === 'handled' ? now() : null,
            ]);

        return back()->with('status', "Status updated for {$count} message(s).");
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:contact_messages,id',
        ]);

        $count = ContactMessage::whereIn('id', $validated['message_ids'])->delete();

        return back()->with('status', "{$count} message(s) deleted successfully.");
    }

    /**
     * Export messages to CSV
     */
    public function export(Request $request)
    {
        $query = ContactMessage::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->date('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->date('end_date'));
        }

        $messages = $query->orderByDesc('created_at')->get();

        $filename = 'contact_messages_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($messages) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Subject',
                'Message',
                'Status',
                'Admin Notes',
                'Handled At',
                'Created At',
            ]);

            // CSV Data
            foreach ($messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->name,
                    $message->email,
                    $message->phone ?? 'N/A',
                    $message->subject ?? 'N/A',
                    $message->message,
                    $message->status,
                    $message->admin_notes ?? 'N/A',
                    $message->handled_at ? $message->handled_at->format('Y-m-d H:i:s') : 'N/A',
                    $message->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

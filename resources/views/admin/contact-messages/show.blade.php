@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Message from '.$contactMessage->name)

@section('header-description', $contactMessage->created_at->format('M d, Y g:i A'))

@section('header-actions')
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Messages
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-semibold text-gray-900">{{ $contactMessage->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Phone</p>
                    <p class="font-semibold text-gray-900">{{ $contactMessage->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Subject</p>
                    <p class="font-semibold text-gray-900">{{ $contactMessage->subject ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        @class([
                            'bg-gray-200 text-gray-700' => $contactMessage->status === 'new',
                            'bg-amber-100 text-amber-800' => $contactMessage->status === 'in_progress',
                            'bg-green-100 text-green-800' => $contactMessage->status === 'handled',
                        ])">
                        {{ Str::headline($contactMessage->status) }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-gray-500 text-sm mb-2">Message</p>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-gray-800 whitespace-pre-line">
                    {{ $contactMessage->message }}
                </div>
            </div>
        </div>

        <!-- Reply Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Send Reply</h2>
            
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.contact-messages.reply', $contactMessage) }}" class="space-y-4" id="reply-form">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reply Channel <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors reply-channel-option">
                            <input type="radio" name="channel" value="email" class="sr-only" checked onchange="updateRecipient()">
                            <div class="flex items-center gap-3 w-full">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Email</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors reply-channel-option">
                            <input type="radio" name="channel" value="sms" class="sr-only" onchange="updateRecipient()">
                            <div class="flex items-center gap-3 w-full">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="font-medium">SMS</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors reply-channel-option">
                            <input type="radio" name="channel" value="whatsapp" class="sr-only" onchange="updateRecipient()">
                            <div class="flex items-center gap-3 w-full">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="font-medium">WhatsApp</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="recipient" class="block text-sm font-medium text-gray-700 mb-1">Recipient <span class="text-red-500">*</span></label>
                    <input type="text" id="recipient" name="recipient" value="{{ $contactMessage->email }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1" id="recipient-hint">Enter email address</p>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea id="message" name="message" rows="6" required maxlength="5000"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                              placeholder="Type your reply message here..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        <span id="char-count">0</span> / 5000 characters
                        <span id="sms-count" class="hidden"> (Approx. <span id="sms-messages">0</span> SMS)</span>
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('reply-form').reset(); updateRecipient();" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                        Clear
                    </button>
                    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg font-semibold hover:bg-teal-800">
                        Send Reply
                    </button>
                </div>
            </form>
        </div>

        <!-- Reply History -->
        @if($contactMessage->replies->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Reply History</h2>
                <div class="space-y-3">
                    @foreach($contactMessage->replies->sortByDesc('created_at') as $reply)
                        <div class="border border-gray-200 rounded-lg p-4 {{ $reply->status === 'failed' ? 'bg-red-50' : 'bg-gray-50' }}">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($reply->status === 'sent') bg-green-100 text-green-800
                                        @elseif($reply->status === 'failed') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ strtoupper($reply->channel) }} - {{ Str::headline($reply->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $reply->created_at->format('M d, Y g:i A') }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">
                                    By {{ $reply->sender->name ?? 'Admin' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 mb-2">
                                <strong>To:</strong> {{ $reply->recipient }}
                            </p>
                            <div class="text-sm text-gray-800 whitespace-pre-line bg-white rounded p-3 border border-gray-200">
                                {{ $reply->message }}
                            </div>
                            @if($reply->error_message)
                                <p class="text-xs text-red-600 mt-2">
                                    <strong>Error:</strong> {{ $reply->error_message }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Update Status</h2>
            <form method="POST" action="{{ route('admin.contact-messages.update', $contactMessage) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                            @foreach (['new' => 'New', 'in_progress' => 'In Progress', 'handled' => 'Handled'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $contactMessage->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                        <textarea name="admin_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">{{ old('admin_notes', $contactMessage->admin_notes) }}</textarea>
                        @error('admin_notes')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold">Save Changes</button>
                </div>
            </form>

            <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" class="delete-contact-message-form text-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 font-semibold">Delete message</button>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        .reply-channel-option input:checked + div {
            color: #0d9488;
        }
        .reply-channel-option:has(input:checked) {
            border-color: #14b8a6;
            background-color: #f0fdfa;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function updateRecipient() {
            const channel = document.querySelector('input[name="channel"]:checked')?.value;
            if (!channel) return;
            
            const recipientInput = document.getElementById('recipient');
            const recipientHint = document.getElementById('recipient-hint');
            const channelOptions = document.querySelectorAll('.reply-channel-option');

            // Update visual selection
            channelOptions.forEach(option => {
                const input = option.querySelector('input[type="radio"]');
                if (input && input.checked) {
                    option.classList.add('border-teal-500', 'bg-teal-50');
                    option.querySelector('svg').classList.remove('text-gray-600');
                    option.querySelector('svg').classList.add('text-teal-600');
                    option.querySelector('span').classList.remove('text-gray-700');
                    option.querySelector('span').classList.add('text-teal-700');
                } else {
                    option.classList.remove('border-teal-500', 'bg-teal-50');
                    option.querySelector('svg').classList.add('text-gray-600');
                    option.querySelector('svg').classList.remove('text-teal-600');
                    option.querySelector('span').classList.add('text-gray-700');
                    option.querySelector('span').classList.remove('text-teal-700');
                }
            });

            // Update recipient field based on channel
            if (channel === 'email') {
                recipientInput.type = 'email';
                recipientInput.value = '{{ $contactMessage->email }}';
                recipientHint.textContent = 'Enter email address';
            } else {
                recipientInput.type = 'tel';
                recipientInput.value = '{{ $contactMessage->phone ?? "" }}';
                recipientHint.textContent = 'Enter phone number (e.g., 0712345678 or +254712345678)';
            }
        }

        // Character counter and SMS calculator
        document.getElementById('message').addEventListener('input', function() {
            const message = this.value;
            const charCount = message.length;
            const charCountEl = document.getElementById('char-count');
            const smsCountEl = document.getElementById('sms-count');
            const smsMessagesEl = document.getElementById('sms-messages');
            const channel = document.querySelector('input[name="channel"]:checked')?.value;

            charCountEl.textContent = charCount;

            // Show SMS count for SMS channel
            if (channel === 'sms') {
                // Standard SMS is 160 characters, longer messages are split
                const smsCount = Math.ceil(charCount / 160);
                smsMessagesEl.textContent = smsCount;
                smsCountEl.classList.remove('hidden');
            } else {
                smsCountEl.classList.add('hidden');
            }
        });

        // Update on channel change
        document.querySelectorAll('input[name="channel"]').forEach(radio => {
            radio.addEventListener('change', function() {
                updateRecipient();
                // Trigger character count update
                document.getElementById('message').dispatchEvent(new Event('input'));
            });
        });

        // Initialize
        updateRecipient();

        // Handle delete form with SweetAlert
        document.querySelector('.delete-contact-message-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formElement = this;
            
            Swal.fire({
                title: 'Delete Message?',
                text: 'Are you sure you want to delete this contact message? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the message.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    formElement.submit();
                }
            });
        });
    </script>
    @endpush
@endsection


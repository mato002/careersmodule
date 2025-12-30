@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('header-description', 'View detailed information about this activity log entry.')

@section('header-actions')
    <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Logs
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            {{ session('warning') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Action Buttons -->
        @if($activityLog->ip_address || $activityLog->user_id)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                @if($activityLog->ip_address)
                    @if($isIpBlocked)
                        <form action="{{ route('admin.blocked-ips.unblock') }}" method="POST" class="inline activity-log-unblock-ip-form" data-ip="{{ $activityLog->ip_address }}">
                            @csrf
                            <input type="hidden" name="ip_address" value="{{ $activityLog->ip_address }}">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                Unblock IP: {{ $activityLog->ip_address }}
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="showBlockIpModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                            Block IP: {{ $activityLog->ip_address }}
                        </button>
                    @endif
                @endif

                @if($activityLog->user_id)
                    @if($isUserBanned)
                        <form action="{{ route('admin.users.unban', $activityLog->user) }}" method="POST" class="inline activity-log-unban-user-form" data-email="{{ $activityLog->user->email }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                Unban User: {{ $activityLog->user->email }}
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.activity-logs.ban-user', $activityLog) }}" method="POST" class="inline activity-log-ban-user-form" data-email="{{ $activityLog->user->email }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold text-sm">
                                Ban User: {{ $activityLog->user->email }}
                            </button>
                        </form>
                        <form action="{{ route('admin.activity-logs.revoke-sessions', $activityLog) }}" method="POST" class="inline activity-log-revoke-sessions-form" data-email="{{ $activityLog->user->email }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-semibold text-sm">
                                Revoke All Sessions
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
        @endif

        <!-- Main Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">User</h3>
                    @if($activityLog->user)
                        <div class="space-y-1">
                            <p class="font-semibold text-gray-900">{{ $activityLog->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $activityLog->user->email }}</p>
                        </div>
                    @else
                        <p class="text-gray-400 italic">System / Guest</p>
                    @endif
                </div>

                <!-- Action -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Action</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        @class([
                            'bg-green-100 text-green-800' => in_array($activityLog->action, ['login', 'create']),
                            'bg-blue-100 text-blue-800' => in_array($activityLog->action, ['update', 'view']),
                            'bg-red-100 text-red-800' => in_array($activityLog->action, ['delete', 'logout', 'login_failed']),
                            'bg-amber-100 text-amber-800' => !in_array($activityLog->action, ['login', 'create', 'update', 'view', 'delete', 'logout', 'login_failed']),
                        ])">
                        {{ Str::headline($activityLog->action) }}
                    </span>
                </div>

                <!-- Date & Time -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Date & Time</h3>
                    <p class="font-semibold text-gray-900">{{ $activityLog->created_at->format('F d, Y g:i A') }}</p>
                    <p class="text-sm text-gray-500">{{ $activityLog->created_at->diffForHumans() }}</p>
                </div>

                <!-- IP Address -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">IP Address</h3>
                    <p class="font-mono text-sm text-gray-900">{{ $activityLog->ip_address ?? '—' }}</p>
                </div>

                <!-- Route -->
                @if($activityLog->route)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Route</h3>
                    <p class="font-mono text-sm text-gray-900">{{ $activityLog->route }}</p>
                </div>
                @endif

                <!-- Model Type -->
                @if($activityLog->model_type)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Related Model</h3>
                    <p class="text-sm text-gray-900">{{ class_basename($activityLog->model_type) }}</p>
                    @if($activityLog->model_id)
                        <p class="text-xs text-gray-500">ID: {{ $activityLog->model_id }}</p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                <p class="text-gray-900 whitespace-pre-line">{{ $activityLog->description }}</p>
            </div>

            <!-- User Agent -->
            @if($activityLog->user_agent)
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">User Agent</h3>
                <p class="text-sm text-gray-900 break-words">{{ $activityLog->user_agent }}</p>
            </div>
            @endif

            <!-- Metadata -->
            @if($activityLog->metadata && count($activityLog->metadata) > 0)
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Additional Information</h3>
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                    <pre class="text-xs text-gray-800 whitespace-pre-wrap font-mono overflow-x-auto">{{ json_encode($activityLog->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Block IP Modal -->
    @if($activityLog->ip_address && !$isIpBlocked)
    <div id="blockIpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Block IP Address</h3>
            <form action="{{ route('admin.activity-logs.block-ip', $activityLog) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">IP Address</label>
                    <input type="text" value="{{ $activityLog->ip_address }}" readonly class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 font-mono text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason (Optional)</label>
                    <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Enter reason for blocking this IP address..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expires At (Optional)</label>
                    <input type="datetime-local" name="expires_at" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave empty for permanent block</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                        Block IP
                    </button>
                    <button type="button" onclick="hideBlockIpModal()" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    // SweetAlert confirmation handlers for activity log actions
    document.addEventListener('DOMContentLoaded', function () {
        // Unblock IP
        document.querySelectorAll('.activity-log-unblock-ip-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const ip = this.getAttribute('data-ip') || 'this IP address';

                Swal.fire({
                    title: 'Unblock IP address?',
                    text: `You are about to unblock IP ${ip}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, unblock',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Unblocking IP...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });

        // Unban user
        document.querySelectorAll('.activity-log-unban-user-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const email = this.getAttribute('data-email') || 'this user';

                Swal.fire({
                    title: 'Unban this user?',
                    text: `You are about to unban ${email}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, unban',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Unbanning user...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });

        // Ban user
        document.querySelectorAll('.activity-log-ban-user-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const email = this.getAttribute('data-email') || 'this user';

                Swal.fire({
                    title: 'Ban this user?',
                    text: `You are about to ban ${email}. All of their active sessions will be revoked.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, ban user',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Banning user...',
                            text: 'Please wait while we revoke all sessions.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });

        // Revoke sessions
        document.querySelectorAll('.activity-log-revoke-sessions-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const email = this.getAttribute('data-email') || 'this user';

                Swal.fire({
                    title: 'Revoke all sessions?',
                    text: `You are about to revoke all active sessions for ${email}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d97706',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, revoke sessions',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Revoking sessions...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });
    });

    function showBlockIpModal() {
        document.getElementById('blockIpModal').classList.remove('hidden');
    }

    function hideBlockIpModal() {
        document.getElementById('blockIpModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('blockIpModal');
        if (event.target === modal) {
            hideBlockIpModal();
        }
    });
</script>
@endpush



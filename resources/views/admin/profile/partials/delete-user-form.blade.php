<section class="space-y-6">
    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-800">
            <strong>Warning:</strong> Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </div>

    <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')
        <input type="hidden" name="password" id="delete-password-input">
    </form>

    <button
        type="button"
        onclick="confirmDeleteAccount()"
        class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition shadow-sm hover:shadow w-full sm:w-auto"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        Delete Account
    </button>
</section>

@push('scripts')
<script>
    function confirmDeleteAccount() {
        Swal.fire({
            title: 'Delete Account?',
            html: `
                <div class="text-left">
                    <p class="mb-4 text-gray-700">Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.</p>
                    <p class="mb-3 text-sm font-medium text-gray-700">Please enter your password to confirm:</p>
                    <input 
                        id="swal-password" 
                        type="password" 
                        class="swal2-input" 
                        placeholder="Enter your password"
                        autocomplete="current-password"
                    >
                    <div id="swal-error" class="text-red-600 text-sm mt-2 hidden"></div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete my account!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusConfirm: false,
            preConfirm: () => {
                const password = document.getElementById('swal-password').value;
                const errorDiv = document.getElementById('swal-error');
                
                if (!password) {
                    errorDiv.textContent = 'Password is required.';
                    errorDiv.classList.remove('hidden');
                    return false;
                }
                
                return password;
            },
            didOpen: () => {
                const passwordInput = document.getElementById('swal-password');
                if (passwordInput) {
                    passwordInput.focus();
                    passwordInput.addEventListener('input', () => {
                        const errorDiv = document.getElementById('swal-error');
                        if (errorDiv) {
                            errorDiv.classList.add('hidden');
                        }
                    });
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Set the password in the hidden input
                document.getElementById('delete-password-input').value = result.value;
                
                // Show loading state
                Swal.fire({
                    title: 'Deleting Account...',
                    text: 'Please wait while we delete your account.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                document.getElementById('delete-account-form').submit();
            }
        });
    }
</script>
@endpush


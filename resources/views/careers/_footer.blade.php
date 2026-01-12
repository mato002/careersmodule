@php
    $generalSettings = \App\Models\GeneralSetting::query()->latest()->first() ?? new \App\Models\GeneralSetting();
@endphp
<!-- Footer -->
<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="text-center">
            <div class="flex items-center justify-center space-x-2 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center">
                    <span class="text-amber-400 font-bold text-xl">F</span>
                </div>
                <span class="text-xl font-bold text-white">{{ $generalSettings->company_name ?? 'Company' }}</span>
            </div>
            <p class="text-sm mb-4">{{ $generalSettings->company_description ?? $generalSettings->footer_text ?? 'Empowering careers and connecting talent with opportunities.' }}</p>
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ $generalSettings->company_name ?? 'Company' }}. {{ $generalSettings->copyright_text ?? 'All rights reserved.' }}</p>
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const isHidden = mobileMenu.classList.toggle('hidden');
            mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
        });
    }

    // Navbar scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (navbar) {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
            
            lastScroll = currentScroll;
        }
    });
</script>









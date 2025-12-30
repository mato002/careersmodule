@extends('layouts.website')

@section('title', 'About Us - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section
        class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden"
        style="background-image: linear-gradient(to bottom right, #115e59, #0f766e, #134e4a);"
    >
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">About Fortress Lenders</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">The Force Of Possibilities</p>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-12 items-start">
                <!-- Narrative -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-5">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.25em] text-teal-700 uppercase mb-2">Who We Are</p>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">
                            Introduction
                        </h2>
                    </div>
                    <p class="text-base sm:text-lg text-gray-700">
                        FORTRESS LENDERS LTD, hereafter referred to as "Fortress," is registered under the Company's Act in the
                        Republic of Kenya, the year 2019 certificate no. PVT-KAUXJED. We are licensed and trade as a Credit only
                        institution as stated in our company memorandum. The Head office is in Fortress Hse, Nakuru County –
                        Barnabas Muguga Opp. Epic ridge Academy.
                    </p>
                    <p class="text-base sm:text-lg text-gray-700">
                        At FORTRESS we exist to enable people to achieve their dreams. This we do through provision of customer
                        centric financial and non-financial solutions. We provide Microfinance and Microcredit products and
                        services in a unique and innovative way. The company was established to respond to the ever-growing need
                        for small business loans.
                    </p>
                    <p class="text-base sm:text-lg text-gray-700">
                        Currently the client base is well over 3000 spread out within Nakuru, Gilgil, Olkalou, Nyahururu and
                        Rumuruti. The company is cognizant of the fact that customers are interested in products that are
                        affordable, diverse and with flexible terms of payment. Fortress therefore undertook to meet these
                        customer requirements and over time, the organization has built a brand that is strong, trusted and
                        appealing.
                    </p>
                    <p class="text-base sm:text-lg text-gray-700">
                        Fortress is interested in seeing our clients start, grow and diversify resulting in increased family
                        income, nutrition, employment and well-being. We achieve this through the provision of financial literacy
                        and business management programs to our clients.
                    </p>
                </div>

                <!-- Key Facts Card -->
                <aside class="bg-gradient-to-br from-teal-900 via-teal-800 to-emerald-700 text-white rounded-2xl shadow-xl px-6 py-6 sm:px-7 sm:py-7">
                    <h3 class="text-lg font-semibold mb-3">Fortress at a Glance</h3>
                    <p class="text-sm text-teal-100 mb-4">
                        Snapshot of who we are and where we operate.
                    </p>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-teal-100">Established</dt>
                            <dd class="font-semibold text-white">2019</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-teal-100">Registration</dt>
                            <dd class="font-semibold text-white text-right">PVT-KAUXJED</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-teal-100">Head Office</dt>
                            <dd class="font-semibold text-white text-right">
                                Fortress Hse, Barnabas Muguga,<br class="hidden sm:block" />
                                Nakuru County
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-teal-100">Client Base</dt>
                            <dd class="font-semibold text-white text-right">3000+ active clients</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-teal-100">Key Locations</dt>
                            <dd class="font-semibold text-white text-right">
                                Nakuru, Gilgil, Olkalou,<br class="hidden sm:block" />
                                Nyahururu, Rumuruti
                            </dd>
                        </div>
                    </dl>
                </aside>
            </div>

            <!-- Downloadable Company Profile -->
            <div class="max-w-4xl mx-auto mt-10">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 rounded-2xl border border-teal-100 bg-teal-50 px-6 py-5 shadow-sm">
                    <div>
                        <h3 class="text-lg font-semibold text-teal-900 mb-1">Download Our Detailed Company Profile</h3>
                        <p class="text-sm text-teal-800">
                            Get the full Fortress Lenders company profile in PDF format with detailed information on our history,
                            governance, products, branch network, and impact.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('company.profile') }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-lg bg-teal-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-teal-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 4v12m0 0l-4-4m4 4l4-4" />
                            </svg>
                            <span>Download Company Profile (PDF)</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values Section -->
    <section class="py-20 bg-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <!-- Mission -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                    <p class="text-gray-600">
                        To provide a full range of financial and non-financial products aimed at improving lives of low income rural and urban communities that will derive great economic impact with increased income levels and restore customer dignity while also increase value for our stakeholders.
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                    <p class="text-gray-600">
                        To be the preferred financial institution providing excellent financial and non-financial solutions through continuous innovation and ensuring sustainable growth for all stakeholders.
                    </p>
                </div>
            </div>

            <!-- Core Values -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Our Core Values</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-teal-700 to-teal-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Integrity</h4>
                        <p class="text-sm text-gray-600">We operate with honesty and transparency</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Excellence</h4>
                        <p class="text-sm text-gray-600">We strive for the highest standards</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Prudence</h4>
                        <p class="text-sm text-gray-600">We manage resources wisely</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Commitment</h4>
                        <p class="text-sm text-gray-600">We are dedicated to our clients</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Teamwork</h4>
                        <p class="text-sm text-gray-600">We work together for success</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Governance Section -->
    <section class="py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">Governance Statement</h2>
                <div class="prose prose-lg text-gray-600 space-y-4">
                    <p>
                        FORTRESS LTD is committed to high standards of corporate governance. The organization is responsible to all its stakeholders for good corporate governance; both in principle and practice. FORTRESS LTD has continuously focused on refining key aspects of its business, physical and organizational infrastructure, information technology systems, products, policies and procedures and the focus on customer centricity, resulting in strong and sustained growth in portfolio and outreach.
                    </p>
                    <p>
                        FORTRESS LTD applies the following strategies to keep up with the good corporate governance requirement.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Board of Directors</h3>
                        <p class="text-gray-600">
                            There is a board of Directors (BOD) composed of executive directors and non-executive directors. The strength of the Board is drawn from the wealth of expertise of the members which cuts across relevant fields of interest to the business of FORTRESS LENDERS LTD.
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Board Committees</h3>
                        <ul class="text-gray-600 space-y-2">
                            <li>• Audit and Compliance committee</li>
                            <li>• Investment committee</li>
                            <li>• Credit committee</li>
                            <li>• Special committee</li>
                            <li>• Complaint committee</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 bg-gray-50" id="team">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-12 text-center">Our Team</h2>
            @if(isset($teamMembers) && $teamMembers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($teamMembers as $member)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-2">
                            <div class="bg-gradient-to-br from-teal-700 to-teal-800 h-48 flex items-center justify-center">
                                <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center border-4 border-white shadow-lg overflow-hidden">
                                    @if ($member->photo_path)
                                        <img src="{{ asset('storage/'.$member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-4xl font-bold text-teal-700">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 text-center break-words">{{ ucwords(strtolower($member->name)) }}</h3>
                                <p class="text-teal-800 font-semibold mb-4 text-center break-words">{{ $member->role }}</p>
                                <p class="text-gray-600 text-sm mb-3 leading-relaxed text-left">
                                    @if($member->bio && strlen($member->bio) > 260)
                                        {{ \Illuminate\Support\Str::words($member->bio, 40, '...') }}
                                    @else
                                        {{ $member->bio ?? '' }}
                                    @endif
                                </p>
                                @if($member->bio && strlen(trim($member->bio)) > 260)
                                    <button 
                                        class="w-full mt-2 px-4 py-2 bg-teal-50 border border-teal-200 text-teal-700 hover:bg-teal-100 hover:border-teal-300 font-semibold text-sm rounded-lg cursor-pointer transition-colors"
                                        data-team-member
                                        data-id="{{ $member->id }}"
                                        data-name="{{ $member->name }}"
                                        data-role="{{ $member->role }}"
                                        data-bio="{{ $member->bio }}"
                                        data-photo="{{ $member->photo_path ? asset('storage/'.$member->photo_path) : '' }}"
                                        data-email="{{ $member->email ?? '' }}"
                                        data-phone="{{ $member->phone ?? '' }}"
                                        data-linkedin="{{ $member->linkedin_url ?? '' }}">
                                        Read Full Bio →
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 bg-white border border-dashed border-gray-200 rounded-2xl py-12">
                    Team information is being updated. Please check back soon.
                </div>
            @endif
        </div>
    </section>

    <!-- Team Member Modal -->
    <div id="teamModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay - transparent with blur effect -->
        <div class="fixed inset-0 transition-opacity z-40" onclick="closeTeamModal()" style="backdrop-filter: blur(10px) saturate(180%); -webkit-backdrop-filter: blur(10px) saturate(180%); pointer-events: auto;"></div>
        
        <!-- Modal container -->
        <div class="relative flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0 z-50">
            <!-- Modal panel -->
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                        <!-- Smaller photo on left -->
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <div id="modalPhoto" class="w-20 h-20 sm:w-16 sm:h-16 rounded-full overflow-hidden bg-gradient-to-br from-teal-600 to-teal-800 text-white flex items-center justify-center text-lg font-semibold border-2 border-gray-100">
                                <!-- Photo will be inserted here -->
                            </div>
                        </div>
                        <!-- Content on right -->
                        <div class="flex-1 min-w-0 text-center sm:text-left">
                            <h3 id="modalName" class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 break-words"></h3>
                            <p id="modalRole" class="text-base text-teal-700 mb-3 break-words"></p>
                            <div id="modalBio" class="text-sm sm:text-base text-gray-600 leading-relaxed mb-4 break-words whitespace-normal"></div>
                            <div id="modalContact" class="space-y-2 text-sm text-gray-600">
                                <!-- Contact info will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeTeamModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizational Structure Section -->
    <section class="py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">Organizational Structure</h2>
                <div class="prose prose-lg text-gray-600 space-y-4">
                    <p>
                        There exist very clear lines of authority and reporting within FORTRESS LTD where competent staffs have been recruited through a rigorous recruitment process. One of the key human resources goals is to ensure that staffs are developed through training, on-the-job induction sessions and skills impartation.
                    </p>
                    <p>
                        The human resource Training needs is identified through a "training need assessment" (TNA) exercises that aim to ensure staff acquire the required skills for them to fulfill their responsibilities efficiently and enable the Company to meet its customer needs adequately.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Information Systems</h3>
                        <p class="text-gray-600">
                            FORTRESS LTD has invested in robust information system software that serves its business lines effectively. This IT software is outsourced and internally customized to meet the unique needs of the Organization.
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Compliance With Regulations</h3>
                        <p class="text-gray-600">
                            FORTRESS LTD is keen on adhering to all regulatory requirements. We are guided by the various statutes which include but not limited to; The Constitution of Kenya, the Company Act, County Government laws and regulations as well as Employment Act among other laws.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Add click handlers to all "Read More" buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-team-member]').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const bio = this.getAttribute('data-bio');
                const photoPath = this.getAttribute('data-photo');
                const email = this.getAttribute('data-email');
                const phone = this.getAttribute('data-phone');
                const linkedinUrl = this.getAttribute('data-linkedin');
                
                openTeamModal(id, name, role, bio, photoPath, email, phone, linkedinUrl);
            });
        });
    });
    
    function openTeamModal(id, name, role, bio, photoPath, email, phone, linkedinUrl) {
        const modal = document.getElementById('teamModal');
        const modalName = document.getElementById('modalName');
        const modalRole = document.getElementById('modalRole');
        const modalBio = document.getElementById('modalBio');
        const modalPhoto = document.getElementById('modalPhoto');
        const modalContact = document.getElementById('modalContact');
        
        // Set name and role with proper formatting
        modalName.textContent = name;
        modalRole.textContent = role;
        // Set bio with proper text wrapping
        modalBio.textContent = bio;
        modalBio.style.wordWrap = 'break-word';
        modalBio.style.overflowWrap = 'break-word';
        
        // Set photo or initials
        if (photoPath) {
            modalPhoto.innerHTML = `<img src="${photoPath}" alt="${name}" class="w-full h-full object-cover">`;
        } else {
            modalPhoto.textContent = name.substring(0, 2).toUpperCase();
        }
        
        // Build contact info
        let contactHtml = '';
        if (email) {
            contactHtml += `
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <a href="mailto:${email}" class="hover:text-teal-700">${email}</a>
                </div>
            `;
        }
        if (phone) {
            const phoneClean = phone.replace(/\s+/g, '');
            contactHtml += `
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2H6a3 3 0 01-3-3V5z"/>
                    </svg>
                    <a href="tel:${phoneClean}" class="hover:text-teal-700">${phone}</a>
                </div>
            `;
        }
        if (linkedinUrl) {
            contactHtml += `
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-700" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/>
                        <path d="M2 9h4v12H2z"/>
                        <circle cx="4" cy="4" r="2"/>
                    </svg>
                    <a href="${linkedinUrl}" target="_blank" rel="noopener" class="hover:text-teal-700">LinkedIn</a>
                </div>
            `;
        }
        modalContact.innerHTML = contactHtml || '<p class="text-gray-400">No contact information available</p>';
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    function closeTeamModal() {
        const modal = document.getElementById('teamModal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTeamModal();
        }
    });
</script>
@endpush

